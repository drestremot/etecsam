<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Mail\LabReservationFinalized;
use App\Models\LabReservation;
use App\Models\Material;
use App\Models\Space;
use App\Models\User;
use App\Services\PushNotificationService;
use App\Services\ReservationNotifier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

    public function index(\Illuminate\Http\Request $request)
    {
        $user = auth()->user();

        // ── Filtros via GET ──
        $filters = $request->only(['status', 'space_id', 'data_inicio', 'data_fim', 'busca']);

        $applyFilters = function ($query) use ($filters, $user) {
            if (!empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (!empty($filters['space_id'])) {
                $query->where('space_id', $filters['space_id']);
            }
            if (!empty($filters['data_inicio'])) {
                $query->whereDate('reservation_date', '>=', $filters['data_inicio']);
            }
            if (!empty($filters['data_fim'])) {
                $query->whereDate('reservation_date', '<=', $filters['data_fim']);
            }
            if (!empty($filters['busca'])) {
                $q = $filters['busca'];
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('space', fn($s) => $s->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('user',  fn($s) => $s->where('name', 'like', "%{$q}%"))
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
        };

        if ($user->is_admin || $user->hasRole('Coordenador')) {
            $pendentes    = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->orderByRaw("CASE status WHEN 'aguardando_validacao' THEN 0 ELSE 1 END")
                ->latest()->get();

            $reservations = LabReservation::with(['user', 'space'])
                ->whereNotIn('status', ['validada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();

        } elseif ($user->hasRole('Auxiliar')) {
            $pendentes    = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['aprovada', 'aguardando_conferencia'])
                ->latest()->get();

            $reservations = LabReservation::with(['user', 'space'])
                ->whereNotIn('status', ['validada', 'recusada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();

        } else {
            $pendentes    = null;
            $reservations = LabReservation::with(['space'])
                ->where('user_id', $user->id)
                ->whereNotIn('status', ['validada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();
        }

        $spaces   = Space::orderBy('name')->get(['id', 'name']);
        $statuses = [
            'pre_alocada'            => 'Aguardando aprovação',
            'aprovada'               => 'Aprovada',
            'em_execucao'            => 'Em execução',
            'aguardando_conferencia' => 'Aguardando conferência',
            'aguardando_validacao'   => 'Aguardando validação',
            'recusada'               => 'Recusada',
        ];

        return view('lab.reservations.index', compact('reservations', 'pendentes', 'user', 'filters', 'spaces', 'statuses'));
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
            'description'      => 'required|string|min:10',
            'mat_ids'          => 'nullable|array',
            'mat_ids.*'        => 'exists:materials,id',
            'mat_qty'          => 'nullable|array',
            'mat_qty.*'        => 'integer|min:1',
        ], [
            'reservation_date.after_or_equal' => 'A reserva deve ser feita com pelo menos 2 dias de antecedência.',
            'description.required'            => 'Descreva o plano de aula.',
            'description.min'                 => 'Descreva o plano de aula com pelo menos 10 caracteres.',
        ]);

        $reservation = LabReservation::create([
            'user_id'          => auth()->id(),
            'space_id'         => $validated['space_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'] ?? null,
            'description'      => $validated['description'],
            'status'           => 'pre_alocada',
        ]);

        // Vincula materiais selecionados com suas quantidades
        $matIds = $request->input('mat_ids', []);
        $matQty = $request->input('mat_qty', []);

        foreach ($matIds as $matId) {
            $qty = max(1, (int) ($matQty[$matId] ?? 1));
            $reservation->materials()->attach($matId, [
                'quantity_requested' => $qty,
            ]);
        }

        $reservation->load(['user', 'space']);
        $title = 'Nova reserva aguardando aprovação';
        $body  = "{$reservation->user?->name} solicitou a reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')}.";
        $this->notifier()->notify(User::coordenadores()->get(), $reservation, $title, $body, auth()->user());

        return redirect()->route('lab.reservations.show', $reservation)
            ->with('success', 'Reserva criada com sucesso! Aguarde aprovação do coordenador.')
            ->with('print_pdf', true);
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
        $reservation->loadMissing(['user', 'space.auxiliar']);

        $title = 'Reserva aprovada';
        $body  = "A reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')} foi aprovada pelo coordenador.";
        $this->notifier()->notify([$reservation->user, $reservation->space?->auxiliar], $reservation, $title, $body, auth()->user());

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
        $reservation->loadMissing(['user', 'space']);

        $title = 'Materiais entregues, aula iniciada';
        $body  = "Os materiais da reserva em {$reservation->space?->name} foram entregues e a aula foi iniciada.";
        $recipients = [$reservation->user, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $reservation, $title, $body, auth()->user());

        return back()->with('success', 'Materiais entregues e checklist assinado. Boa aula!');
    }

    // ── Professor: observações e liberação ──
    public function submitProfessorObs(Request $request, LabReservation $reservation)
    {
        $request->validate([
            'obs' => 'required|string|min:10',
        ], [
            'obs.required' => 'Informe suas observações sobre a aula.',
            'obs.min'      => 'A observação deve ter pelo menos 10 caracteres.',
        ]);

        $data = [
            'obs'                   => $request->obs,
            'professor_released_at' => now(),
        ];

        // Se auxiliar já liberou → ambos liberaram → aguardando coordenador
        if ($reservation->auxiliar_released_at) {
            $data['status'] = 'aguardando_validacao';
        }
        // Senão: só atualiza obs e professor_released_at, status permanece
        // O auxiliar poderá liberar depois e mudará para aguardando_validacao

        $reservation->update($data);

        $fresh = $reservation->fresh();
        $msg = $fresh->status === 'aguardando_validacao'
            ? 'Observações registradas e atividade enviada ao coordenador para validação!'
            : 'Observações registradas! Aguardando o auxiliar liberar para enviar ao coordenador.';

        $fresh->loadMissing(['space.auxiliar', 'user']);
        $title = 'Observações da aula registradas';
        $body  = "{$fresh->user?->name} registrou as observações da reserva em {$fresh->space?->name}.";
        $recipients = [$fresh->space?->auxiliar, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $fresh, $title, $body, auth()->user());

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

        // Se professor já liberou → aguardando_validacao
        // Senão → permanece em_execucao mas marca auxiliar_released_at
        // O professor ainda poderá liberar e o status mudará para aguardando_validacao
        if ($reservation->professor_released_at) {
            $data['status'] = 'aguardando_validacao';
        } else {
            $data['status'] = 'em_execucao'; // mantém para o professor ainda poder liberar
        }

        $reservation->update($data);
        $reservation->loadMissing(['auxiliar', 'user', 'space']);

        $msg = $data['status'] === 'aguardando_validacao'
            ? 'Conferência registrada. Reserva enviada ao coordenador para validação!'
            : 'Conferência registrada. Aguardando o professor registrar as observações.';

        $title = 'Conferência do auxiliar registrada';
        $body  = "{$reservation->auxiliar?->name} concluiu a conferência da reserva em {$reservation->space?->name}.";
        $recipients = [$reservation->user, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $reservation, $title, $body, auth()->user());

        return back()->with('success', $msg);
    }

    // ── Coordenador / Admin: validar e arquivar ──
    public function validateActivity(Request $request, LabReservation $reservation)
    {
        $request->validate([
            'coordenador_obs' => 'nullable|string|max:2000',
        ]);

        $reservation->update([
            'status'          => 'validada',
            'validated_at'    => now(),
            'coordenador_obs' => $request->coordenador_obs,
            'coordenador_id'  => auth()->id(),
        ]);

        $reservation->load(['user', 'space', 'auxiliar', 'coordenador', 'materials']);

        // Envia e-mail ao professor
        try {
            if ($reservation->user?->email) {
                Mail::to($reservation->user->email)
                    ->send(new LabReservationFinalized($reservation, 'professor'));
            }
            // Envia e-mail ao auxiliar
            if ($reservation->auxiliar?->email) {
                Mail::to($reservation->auxiliar->email)
                    ->send(new LabReservationFinalized($reservation, 'auxiliar'));
            }
            // Envia cópia ao coordenador que validou
            if ($reservation->coordenador?->email) {
                Mail::to($reservation->coordenador->email)
                    ->send(new LabReservationFinalized($reservation, 'coordenador'));
            }
        } catch (\Exception $e) {
            // Ignora falha de e-mail — validação já foi salva
        }

        $title = 'Atividade validada e arquivada';
        $body  = "A reserva em {$reservation->space?->name} foi validada pelo coordenador.";
        $this->pushService()->sendToUsers([$reservation->user, $reservation->auxiliar], $title, $body, $this->pushData($reservation));

        return redirect()->route('lab.reservations.show', $reservation)
            ->with('success', 'Atividade validada e arquivada! Notificações enviadas ao professor e auxiliar.')
            ->with('print_pdf', true);
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
            ->whereIn('status', ['validada', 'concluida', 'finalizada'])
            ->orderByRaw('COALESCE(finalized_at, validated_at) DESC')
            ->paginate(20);

        return view('lab.reservations.history', compact('reservations'));
    }

    public function generatePDF(LabReservation $reservation)
    {
        $reservation->load(['user', 'space', 'auxiliar', 'materials']);
        $pdf = Pdf::loadView('lab.reservations.pdf', compact('reservation'));
        return $pdf->stream("checklist-reserva-{$reservation->id}.pdf");
    }

    public function availability(Space $space)
    {
        $reservations = LabReservation::where('space_id', $space->id)
            ->whereNotIn('status', ['recusada', 'validada'])
            ->where('reservation_date', '>=', now()->startOfMonth())
            ->where('reservation_date', '<=', now()->addMonths(3)->endOfMonth())
            ->get(['id', 'reservation_date', 'start_time', 'end_time', 'status'])
            ->map(fn($r) => [
                'date'       => $r->reservation_date->format('Y-m-d'),
                'start'      => substr($r->start_time, 0, 5),
                'end'        => $r->end_time ? substr($r->end_time, 0, 5) : null,
                'status'     => $r->status,
                'occupied'   => true,
            ]);

        return response()->json($reservations);
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

    private function pushService(): PushNotificationService
    {
        return app(PushNotificationService::class);
    }

    private function pushData(LabReservation $reservation): array
    {
        return [
            'type'           => 'reservation_update',
            'reservation_id' => (string) $reservation->id,
        ];
    }

    private function notifier(): ReservationNotifier
    {
        return app(ReservationNotifier::class);
    }
}
