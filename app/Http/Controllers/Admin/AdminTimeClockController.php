<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegalLeaveRequest;
use App\Models\MedicalCertificate;
use App\Models\TimeClockRecord;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Services\AuditLogger;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTimeClockController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $query = TimeClockRecord::with(['user.teacher', 'unit', 'workSchedule'])
            ->whereDate('recorded_at', $date)
            ->orderByDesc('recorded_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->paginate(30)->withQueryString();

        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email', 'role']);
        $units = Unit::where('is_active', true)->orderBy('name')->get(['id', 'name', 'city']);

        // Estatísticas do dia
        $todayRecords = TimeClockRecord::whereDate('recorded_at', $date)->get();
        $stats = [
            'total_punches'    => $todayRecords->count(),
            'distinct_users'   => $todayRecords->pluck('user_id')->unique()->count(),
            'flagged_outside'  => $todayRecords->where('status', 'flagged_outside_unit')->count(),
            'flagged_late'     => $todayRecords->where('status', 'flagged_late')->count(),
            'approved_punches' => $todayRecords->where('status', 'approved')->count(),
        ];

        return view('admin.timeclock.index', compact('records', 'users', 'units', 'stats', 'date'));
    }

    public function mirror(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $selectedUserId = $request->input('user_id', $users->first()?->id);
        $user = User::with('teacher')->find($selectedUserId) ?: $users->first();

        $monthYear = $request->input('month', Carbon::today()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $monthYear)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Carregar batidas do mês do usuário
        $monthRecords = TimeClockRecord::with('unit')
            ->where('user_id', $user->id)
            ->whereBetween('recorded_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->orderBy('recorded_at')
            ->get()
            ->groupBy(fn($r) => $r->recorded_at->format('Y-m-d'));

        // 2. Carregar jornadas semanais cadastradas do usuário
        $schedules = WorkSchedule::with('unit')
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        // 3. Carregar Atestados Médicos do mês
        $medicalCertificates = MedicalCertificate::where('user_id', $user->id)
            ->where('status', 'homologado')
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                  ->orWhereBetween('end_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
            })->get();

        // 4. Carregar Folgas Legais aprovadas
        $legalLeaves = LegalLeaveRequest::where('user_id', $user->id)
            ->where('status', 'aprovado')
            ->whereBetween('requested_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->get();

        // 5. Montar os dias do mês
        $days = [];
        $totalMinutesWorked = 0;
        $totalMinutesExpected = 0;
        $totalDelays = 0;

        $period = CarbonPeriod::create($startDate, $endDate);
        foreach ($period as $currentDay) {
            $dayStr = $currentDay->format('Y-m-d');
            $dayOfWeek = $currentDay->dayOfWeek; // 0=Dom, 1=Seg, ...

            $daySchedules = $schedules[$dayOfWeek] ?? collect();
            $dayPunches = $monthRecords[$dayStr] ?? collect();

            // Verificar se há atestado
            $hasMedical = $medicalCertificates->first(function ($med) use ($dayStr) {
                return $dayStr >= $med->start_date && $dayStr <= $med->end_date;
            });

            // Verificar se há folga
            $hasLeave = $legalLeaves->firstWhere('requested_date', $dayStr);

            // Calcular horas trabalhadas no dia
            $punches = [
                'entry_1' => $dayPunches->firstWhere('record_type', 'entry_1'),
                'exit_1'  => $dayPunches->firstWhere('record_type', 'exit_1'),
                'entry_2' => $dayPunches->firstWhere('record_type', 'entry_2'),
                'exit_2'  => $dayPunches->firstWhere('record_type', 'exit_2'),
            ];

            $dayWorkedMinutes = 0;
            if ($punches['entry_1'] && $punches['exit_1']) {
                $dayWorkedMinutes += $punches['exit_1']->recorded_at->diffInMinutes($punches['entry_1']->recorded_at);
            }
            if ($punches['entry_2'] && $punches['exit_2']) {
                $dayWorkedMinutes += $punches['exit_2']->recorded_at->diffInMinutes($punches['entry_2']->recorded_at);
            }
            if ($punches['entry_1'] && $punches['exit_2'] && !$punches['exit_1'] && !$punches['entry_2']) {
                $dayWorkedMinutes = $punches['exit_2']->recorded_at->diffInMinutes($punches['entry_1']->recorded_at);
            }

            $dayExpectedMinutes = 0;
            foreach ($daySchedules as $ds) {
                $dayExpectedMinutes += $ds->getPlannedDurationMinutes();
            }

            $totalMinutesWorked += $dayWorkedMinutes;
            $totalMinutesExpected += $dayExpectedMinutes;
            $totalDelays += $dayPunches->sum('delay_minutes');

            $days[] = [
                'date'             => $currentDay->copy(),
                'day_name'         => WorkSchedule::getDaysList()[$dayOfWeek] ?? '',
                'day_short'        => match($dayOfWeek) { 1=>'SEG', 2=>'TER', 3=>'QUA', 4=>'QUI', 5=>'SEX', 6=>'SÁB', default=>'DOM' },
                'schedules'        => $daySchedules,
                'punches'          => $punches,
                'raw_punches'      => $dayPunches,
                'worked_minutes'   => $dayWorkedMinutes,
                'expected_minutes' => $dayExpectedMinutes,
                'delays'           => $dayPunches->sum('delay_minutes'),
                'medical_cert'     => $hasMedical,
                'legal_leave'      => $hasLeave,
            ];
        }

        $summary = [
            'total_worked_hours'   => sprintf('%02dh %02dmin', floor($totalMinutesWorked / 60), $totalMinutesWorked % 60),
            'total_expected_hours' => sprintf('%02dh %02dmin', floor($totalMinutesExpected / 60), $totalMinutesExpected % 60),
            'balance_minutes'      => $totalMinutesWorked - $totalMinutesExpected,
            'total_delays_minutes' => $totalDelays,
        ];

        return view('admin.timeclock.mirror', compact('user', 'users', 'monthYear', 'days', 'summary'));
    }

    public function justify(Request $request, TimeClockRecord $timeClockRecord): JsonResponse
    {
        $request->validate([
            'justification' => 'required|string|max:500',
        ]);

        $timeClockRecord->update([
            'status'        => 'justified',
            'justification' => $request->justification,
            'justified_by'  => Auth::id(),
            'justified_at'  => now(),
        ]);

        AuditLogger::log(
            action: 'updated',
            module: 'Ponto Eletrônico',
            description: "Justificou ocorrência de ponto de {$timeClockRecord->user->name}: {$request->justification}",
            auditable: $timeClockRecord
        );

        return response()->json([
            'success'      => true,
            'message'      => 'Ponto justificado e regularizado!',
            'status_label' => $timeClockRecord->getStatusLabel(),
            'status_badge' => $timeClockRecord->getStatusBadgeClass(),
        ]);
    }
}

