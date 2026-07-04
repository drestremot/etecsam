<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\LabReservation;
use App\Models\Material;
use App\Models\Space;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LabReservationController extends Controller
{
    public function dashboard()
    {
        $user    = auth()->user();
        $teacher = \App\Models\Teacher::where('email', $user->email)->first();

        // Stats adaptados por papel
        if ($user->is_admin) {
            $stats = [
                'spaces'    => Space::count(),
                'materials' => Material::count(),
                'pending'   => LabReservation::where('status', 'pre_alocada')->count(),
                'active'    => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
            ];
            $recent = LabReservation::with(['user', 'space'])->latest()->take(5)->get();
        } elseif ($user->hasRole('Coordenador')) {
            $stats = [
                'aguardando_aprovacao' => LabReservation::where('status', 'pre_alocada')->count(),
                'aguardando_validacao' => LabReservation::where('status', 'aguardando_validacao')->count(),
                'ativas'               => LabReservation::whereIn('status', ['aprovada', 'em_execucao', 'aguardando_conferencia'])->count(),
                'validadas'            => LabReservation::where('status', 'validada')->count(),
            ];
            $recent = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->latest()->take(5)->get();
        } elseif ($user->hasRole('Auxiliar')) {
            $stats = [
                'aguardando' => LabReservation::whereIn('status', ['aprovada', 'aguardando_conferencia'])->count(),
                'ativas'     => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas' => LabReservation::whereIn('status', ['validada', 'concluida', 'finalizada'])->count(),
                'total'      => LabReservation::count(),
            ];
            $recent = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['aprovada', 'aguardando_conferencia', 'em_execucao'])
                ->latest()->take(5)->get();
        } else {
            // Professor
            $stats = [
                'minhas'    => LabReservation::where('user_id', $user->id)->count(),
                'pendentes' => LabReservation::where('user_id', $user->id)->where('status', 'pre_alocada')->count(),
                'ativas'    => LabReservation::where('user_id', $user->id)->whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas'=> LabReservation::where('user_id', $user->id)->whereIn('status', ['concluida', 'finalizada'])->count(),
            ];
            $recent = LabReservation::with(['space'])
                ->where('user_id', $user->id)
                ->latest()->take(5)->get();
        }

        return view('lab.dashboard', compact('stats', 'recent', 'teacher', 'user'));
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->is_admin || $user->hasRole('Coordenador')) {
            $pendentes    = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->orderByRaw("CASE status WHEN 'aguardando_validacao' THEN 0 ELSE 1 END")
                ->latest()->get();
            $reservations = LabReservation::with(['user', 'space'])->latest()->paginate(20);
        } elseif ($user->hasRole('Auxiliar')) {
            $pendentes    = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['aprovada', 'aguardando_conferencia'])
                ->latest()->get();
            $reservations = LabReservation::with(['user', 'space'])
                ->whereNotIn('status', ['validada', 'recusada'])
                ->latest()->paginate(20);
        } else {
            $pendentes    = null;
            $reservations = LabReservation::with(['space'])
                ->where('user_id', $user->id)->latest()->paginate(20);
        }

        return view('lab.reservations.index', compact('reservations', 'pendentes', 'user'));
    }

    public function create()
    {
        $spaces    = Space::orderBy('name')->get();
        $materials = Material::orderBy('name')->get();
        return view('lab.reservations.create', compact('spaces', 'materials'));
    }

    public function store(Request $request)
    {
        $minDate = now()->addDays(2)->format('Y-m-d');

        $validated = $request->validate([
            'space_id'         => 'required|exists:spaces,id',
            'reservation_date' => "required|date|after_or_equal:{$minDate}",
            'start_time'       => 'required',
            'end_time'         => 'nullable',
            'description'      => 'nullable|string',
            'materials'        => 'nullable|array',
            'materials.*.id'   => 'exists:materials,id',
            'materials.*.qty'  => 'integer|min:1',
        ], [
            'reservation_date.after_or_equal' => 'A reserva deve ser feita com pelo menos 2 dias de antecedência.',
        ]);

        $reservation = LabReservation::create([
            'user_id'          => auth()->id(),
            'space_id'         => $validated['space_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'] ?? null,
            'description'      => $validated['description'] ?? null,
            'status'           => 'pre_alocada',
        ]);

        if (!empty($validated['materials'])) {
            foreach ($validated['materials'] as $mat) {
                $reservation->materials()->attach($mat['id'], [
                    'quantity_requested' => $mat['qty'] ?? 1,
                ]);
            }
        }

        return redirect()->route('lab.reservations.show', $reservation)
            ->with('success', 'Reserva criada! Aguarde aprovação do coordenador (mínimo 2 dias de antecedência respeitado).');
    }

    public function show(LabReservation $reservation)
    {
        $reservation->load(['user', 'space', 'auxiliar', 'coordenador', 'materials', 'images']);
        return view('lab.reservations.show', compact('reservation'));
    }

    // ── Coordenador / Admin: aprovar e encaminhar ao auxiliar ──
    public function approve(LabReservation $reservation)
    {
        $reservation->update([
            'status'         => 'aprovada',
            'coordenador_id' => auth()->id(),
        ]);
        return back()->with('success', 'Reserva aprovada! O auxiliar foi notificado para preparar o laboratório.');
    }

    public function reject(LabReservation $reservation)
    {
        $reservation->update([
            'status'         => 'recusada',
            'coordenador_id' => auth()->id(),
        ]);
        return back()->with('success', 'Reserva recusada.');
    }

    // ── Auxiliar: entregar materiais + professor assina ──
    public function startClass(LabReservation $reservation)
    {
        $reservation->update([
            'status'               => 'em_execucao',
            'professor_signed_at'  => now(),
        ]);
        return back()->with('success', 'Materiais entregues e checklist assinado. Boa aula!');
    }

    // ── Professor: observações e liberação ──
    public function submitProfessorObs(Request $request, LabReservation $reservation)
    {
        $request->validate(['obs' => 'required|string|min:5'], [
            'obs.required' => 'Informe suas observações sobre a aula.',
        ]);

        $data = [
            'obs'                   => $request->obs,
            'professor_released_at' => now(),
        ];

        // Se auxiliar já liberou, vai para aguardando_validacao
        if ($reservation->auxiliar_released_at) {
            $data['status'] = 'aguardando_validacao';
        }

        $reservation->update($data);

        $msg = $reservation->fresh()->status === 'aguardando_validacao'
            ? 'Observações registradas. Reserva enviada ao coordenador para validação!'
            : 'Observações registradas. Aguardando o auxiliar liberar também.';

        return back()->with('success', $msg);
    }

    // ── Auxiliar: conferência e liberação ──
    public function auxiliarFinalize(Request $request, LabReservation $reservation)
    {
        $request->validate([
            'auxiliar_obs' => 'required|string|min:5',
        ], [
            'auxiliar_obs.required' => 'Informe as observações da conferência.',
        ]);

        $data = [
            'auxiliar_obs'            => $request->auxiliar_obs,
            'auxiliar_id'             => auth()->id(),
            'auxiliar_released_at'    => now(),
            'confirmed_by_auxiliar_at' => now(),
        ];

        // Se professor já liberou, vai para aguardando_validacao
        if ($reservation->professor_released_at) {
            $data['status'] = 'aguardando_validacao';
        } else {
            $data['status'] = 'aguardando_conferencia';
        }

        $reservation->update($data);

        $msg = $data['status'] === 'aguardando_validacao'
            ? 'Conferência registrada. Reserva enviada ao coordenador para validação!'
            : 'Conferência registrada. Aguardando o professor registrar as observações.';

        return back()->with('success', $msg);
    }

    // ── Coordenador / Admin: validar e arquivar ──
    public function validateActivity(LabReservation $reservation)
    {
        $reservation->update([
            'status'       => 'validada',
            'validated_at' => now(),
        ]);
        return back()->with('success', 'Atividade validada e arquivada com sucesso!');
    }

    public function uploadScannedDoc(Request $request, LabReservation $reservation)
    {
        $request->validate(['scanned_doc' => 'required|file|mimes:pdf,jpg,png|max:5120']);
        $path = $request->file('scanned_doc')->store('reservations/docs', 'public');
        $reservation->update([
            'scanned_doc'  => $path,
            'status'       => 'concluida',
            'finalized_at' => now(),
        ]);
        return back()->with('success', 'Documento enviado. Reserva concluída!');
    }

    public function history()
    {
        $reservations = LabReservation::with(['user', 'space'])
            ->whereIn('status', ['concluida', 'finalizada'])
            ->latest('finalized_at')
            ->paginate(20);

        return view('lab.reservations.history', compact('reservations'));
    }

    public function generatePDF(LabReservation $reservation)
    {
        $reservation->load(['user', 'space', 'auxiliar', 'materials']);
        $pdf = Pdf::loadView('lab.reservations.pdf', compact('reservation'));
        return $pdf->stream("checklist-reserva-{$reservation->id}.pdf");
    }

    public function calendarEvents()
    {
        $reservations = LabReservation::with('space')
            ->whereNotIn('status', ['recusada'])
            ->get()
            ->map(fn($r) => [
                'id'    => $r->id,
                'title' => $r->space->name . ' — ' . ($r->user->name ?? ''),
                'start' => $r->reservation_date->format('Y-m-d') . 'T' . $r->start_time,
                'end'   => $r->reservation_date->format('Y-m-d') . 'T' . ($r->end_time ?? $r->start_time),
                'color' => match($r->status) {
                    'pre_alocada' => '#9ca3af',
                    'aprovada'    => '#3b82f6',
                    'em_execucao' => '#f59e0b',
                    'concluida', 'finalizada' => '#22c55e',
                    'recusada'    => '#ef4444',
                    default       => '#6b7280',
                },
                'url'   => route('lab.reservations.show', $r->id),
            ]);

        return response()->json($reservations);
    }
}
