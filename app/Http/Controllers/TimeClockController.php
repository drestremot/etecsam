<?php

namespace App\Http\Controllers;

use App\Models\TimeClockRecord;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use App\Services\AuditLogger;
use App\Services\GeoLocationService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TimeClockController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek; // 0 = Domingo, 1 = Segunda, ..., 6 = Sábado

        // Carregar todas as unidades com coordenadas
        $units = Unit::where('is_active', true)->orderBy('name')->get();

        // Carregar jornada do usuário para o dia de hoje
        $todaySchedules = WorkSchedule::with('unit')
            ->where('user_id', $user->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        // Batidas registradas hoje
        $todayRecords = TimeClockRecord::with('unit')
            ->where('user_id', $user->id)
            ->whereDate('recorded_at', $today)
            ->orderBy('recorded_at')
            ->get();

        $formattedTodayRecords = $todayRecords->map(fn($r) => [
            'id'           => $r->id,
            'type_label'   => $r->getRecordTypeLabel(),
            'time'         => $r->recorded_at->format('H:i:s'),
            'unit_name'    => $r->unit?->name ?? 'Unidade',
            'status_label' => $r->getStatusLabel(),
            'status_badge' => $r->getStatusBadgeClass(),
            'photo_url'    => $r->photo_url,
        ])->values()->toArray();

        // Próximo tipo de batida sugerido
        $nextRecordType = $this->determineNextRecordType($todayRecords->count());

        // Foto oficial de referência do cadastro (Professor ou Usuário)
        $referencePhoto = $user->teacher?->photo ? photo_url($user->teacher->photo) : ($user->profile_photo ? Storage::url($user->profile_photo) : null);

        // Histórico recente dos últimos 7 dias
        $recentRecords = TimeClockRecord::with(['unit', 'workSchedule'])
            ->where('user_id', $user->id)
            ->whereDate('recorded_at', '>=', now()->subDays(7))
            ->orderByDesc('recorded_at')
            ->get();

        return view('timeclock.index', compact(
            'user',
            'units',
            'todaySchedules',
            'todayRecords',
            'formattedTodayRecords',
            'nextRecordType',
            'referencePhoto',
            'recentRecords'
        ));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'unit_id'      => 'nullable|exists:units,id',
            'record_type'  => 'required|string|in:entry_1,exit_1,entry_2,exit_2,extra_entry,extra_exit',
            'photo_base64' => 'required|string',
            'latitude'     => 'nullable|numeric',
            'longitude'    => 'nullable|numeric',
            'accuracy'     => 'nullable|numeric',
        ]);

        $user = Auth::user();
        $now = now();
        $dayOfWeek = $now->dayOfWeek;

        // 1. Processar e salvar a foto capturada pela câmera
        $photoPath = $this->savePhotoFromBase64($request->photo_base64, $user->id);

        // 2. Identificar Unidade e Geolocalização (GPS)
        $unit = null;
        $distance = null;
        $isWithinGeofence = false;

        if ($request->filled('unit_id')) {
            $unit = Unit::find($request->unit_id);
        }

        // Se o usuário passou GPS mas não escolheu unidade, buscar a unidade mais próxima
        if ($request->filled('latitude') && $request->filled('longitude')) {
            $userLat = (float) $request->latitude;
            $userLon = (float) $request->longitude;

            if (!$unit) {
                $closestUnit = null;
                $minDist = PHP_INT_MAX;

                foreach (Unit::whereNotNull('latitude')->whereNotNull('longitude')->get() as $u) {
                    $d = GeoLocationService::calculateDistance($userLat, $userLon, (float)$u->latitude, (float)$u->longitude);
                    if ($d < $minDist) {
                        $minDist = $d;
                        $closestUnit = $u;
                    }
                }
                $unit = $closestUnit;
            }

            if ($unit && $unit->latitude && $unit->longitude) {
                $geoCheck = GeoLocationService::isWithinGeofence(
                    $userLat,
                    $userLon,
                    (float)$unit->latitude,
                    (float)$unit->longitude,
                    $unit->radius_meters ?: 300
                );
                $isWithinGeofence = $geoCheck['is_within'];
                $distance = $geoCheck['distance_meters'];
            }
        }

        // 3. Verificar Grade Horária e Atrasos
        $workSchedule = WorkSchedule::where('user_id', $user->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->when($unit, fn($q) => $q->where('unit_id', $unit->id))
            ->first();

        $delayMinutes = 0;
        $isWithinSchedule = true;

        if ($workSchedule) {
            $scheduledStart = Carbon::createFromTimeString($workSchedule->start_time);
            $currentTimeOnly = Carbon::createFromTimeString($now->format('H:i:s'));

            // Se for primeira entrada e passou da tolerância
            if ($request->record_type === 'entry_1') {
                $tolerance = $workSchedule->tolerance_minutes ?: 15;
                if ($currentTimeOnly->greaterThan($scheduledStart->copy()->addMinutes($tolerance))) {
                    $delayMinutes = $currentTimeOnly->diffInMinutes($scheduledStart);
                }
            }
        } else {
            // Se não tinha horário programado para hoje
            $isWithinSchedule = false;
        }

        // 4. Determinar Status do Ponto
        $status = 'approved';
        if ($unit && $unit->latitude && !$isWithinGeofence) {
            $status = 'flagged_outside_unit';
        } elseif ($delayMinutes > 0) {
            $status = 'flagged_late';
        } elseif (!$isWithinSchedule) {
            $status = 'flagged_extra';
        }

        // 5. Criar Registro de Ponto
        $record = TimeClockRecord::create([
            'user_id'                 => $user->id,
            'unit_id'                 => $unit?->id,
            'work_schedule_id'        => $workSchedule?->id,
            'recorded_at'             => $now,
            'record_type'             => $request->record_type,
            'verification_method'     => 'facial_recognition',
            'photo_snapshot'          => $photoPath,
            'latitude'                => $request->latitude,
            'longitude'               => $request->longitude,
            'accuracy_meters'         => $request->accuracy ? (int) $request->accuracy : null,
            'distance_to_unit_meters' => $distance,
            'is_within_geofence'      => $isWithinGeofence,
            'is_within_schedule'      => $isWithinSchedule,
            'delay_minutes'           => $delayMinutes,
            'status'                  => $status,
            'ip_address'              => $request->ip(),
            'user_agent'              => $request->header('User-Agent'),
        ]);

        // Registrar auditoria
        AuditLogger::log(
            action: 'created',
            module: 'Ponto Eletrônico',
            description: "Registrou ponto [{$record->getRecordTypeLabel()}] em " . ($unit->name ?? 'Unidade Geral') . " com Reconhecimento Facial.",
            auditable: $record
        );

        return response()->json([
            'success'      => true,
            'message'      => 'Ponto registrado com sucesso!',
            'record'       => [
                'id'              => $record->id,
                'type_label'      => $record->getRecordTypeLabel(),
                'time'            => $now->format('H:i:s'),
                'date'            => $now->format('d/m/Y'),
                'unit_name'       => $unit?->name ?? 'Unidade não informada',
                'distance_meters' => $distance,
                'status_label'    => $record->getStatusLabel(),
                'status_badge'    => $record->getStatusBadgeClass(),
                'photo_url'       => $record->photo_url,
                'delay_minutes'   => $delayMinutes,
                'hash_receipt'    => strtoupper(substr(md5($record->id . $now->timestamp), 0, 16)),
            ]
        ]);
    }

    public function totem(Request $request, ?Unit $unit = null)
    {
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $selectedUnit = $unit ?: $units->first();

        // Professores e colaboradores vinculados a esta unidade ou que lecionam hoje
        $today = Carbon::today();
        $dayOfWeek = $today->dayOfWeek;

        $employees = User::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'registration_number', 'role']);

        return view('timeclock.totem', compact('units', 'selectedUnit', 'employees'));
    }

    public function totemStore(Request $request): JsonResponse
    {
        $request->validate([
            'unit_id'      => 'required|exists:units,id',
            'user_id'      => 'required|exists:users,id',
            'record_type'  => 'required|string|in:entry_1,exit_1,entry_2,exit_2,extra_entry,extra_exit',
            'photo_base64' => 'required|string',
        ]);

        $user = User::findOrFail($request->user_id);
        $unit = Unit::findOrFail($request->unit_id);
        $now = now();
        $dayOfWeek = $now->dayOfWeek;

        $photoPath = $this->savePhotoFromBase64($request->photo_base64, $user->id);

        $workSchedule = WorkSchedule::where('user_id', $user->id)
            ->where('unit_id', $unit->id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        $delayMinutes = 0;
        $isWithinSchedule = true;

        if ($workSchedule && $request->record_type === 'entry_1') {
            $scheduledStart = Carbon::createFromTimeString($workSchedule->start_time);
            $currentTimeOnly = Carbon::createFromTimeString($now->format('H:i:s'));
            $tolerance = $workSchedule->tolerance_minutes ?: 15;
            if ($currentTimeOnly->greaterThan($scheduledStart->copy()->addMinutes($tolerance))) {
                $delayMinutes = $currentTimeOnly->diffInMinutes($scheduledStart);
            }
        }

        $record = TimeClockRecord::create([
            'user_id'                 => $user->id,
            'unit_id'                 => $unit->id,
            'work_schedule_id'        => $workSchedule?->id,
            'recorded_at'             => $now,
            'record_type'             => $request->record_type,
            'verification_method'     => 'totem_kiosk',
            'photo_snapshot'          => $photoPath,
            'is_within_geofence'      => true, // Batido no terminal físico da própria escola
            'is_within_schedule'      => $isWithinSchedule,
            'delay_minutes'           => $delayMinutes,
            'status'                  => $delayMinutes > 0 ? 'flagged_late' : 'approved',
            'ip_address'              => $request->ip(),
            'user_agent'              => 'Totem ' . ($unit->name ?? 'Unidade'),
        ]);

        AuditLogger::log(
            action: 'created',
            module: 'Ponto Eletrônico',
            description: "Registro de Ponto no Totem da {$unit->name} para {$user->name}.",
            auditable: $record
        );

        return response()->json([
            'success' => true,
            'message' => "Ponto de {$user->name} registrado no Totem com sucesso!",
            'record'  => [
                'user_name'    => $user->name,
                'type_label'   => $record->getRecordTypeLabel(),
                'time'         => $now->format('H:i:s'),
                'date'         => $now->format('d/m/Y'),
                'unit_name'    => $unit->name,
                'status_label' => $record->getStatusLabel(),
                'photo_url'    => $record->photo_url,
                'hash_receipt' => strtoupper(substr(md5($record->id . $now->timestamp), 0, 16)),
            ]
        ]);
    }

    private function determineNextRecordType(int $punchesCountToday): string
    {
        return match ($punchesCountToday) {
            0 => 'entry_1',
            1 => 'exit_1',
            2 => 'entry_2',
            3 => 'exit_2',
            default => 'extra_entry',
        };
    }

    private function savePhotoFromBase64(string $base64Data, int $userId): string
    {
        // Se vier com prefixo "data:image/jpeg;base64,"
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
            $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
            $type = strtolower($type[1]);
        } else {
            $type = 'jpg';
        }

        $image = base64_decode($base64Data);
        $fileName = 'ponto_fotos/' . date('Y/m') . '/ponto_' . $userId . '_' . time() . '_' . Str::random(6) . '.' . $type;

        Storage::disk('public')->put($fileName, $image);

        return $fileName;
    }
}
