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
            $meus = fn ($q) => $q->where('coordenador_id', $user->id)->orWhereNull('coordenador_id');

            $stats = [
                'aguardando_aprovacao' => LabReservation::where('status', 'pre_alocada')->where($meus)->count(),
                'aguardando_validacao' => LabReservation::where('status', 'aguardando_validacao')->where('coordenador_id', $user->id)->count(),
                'ativas'               => LabReservation::whereIn('status', ['aprovada', 'em_execucao', 'aguardando_conferencia'])->where('coordenador_id', $user->id)->count(),
                'validadas'            => LabReservation::where('status', 'validada')->where('coordenador_id', $user->id)->count(),
            ];
            $recent = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->where(fn ($q) => $q->where('coordenador_id', $user->id)->orWhere(fn ($s) => $s->whereNull('coordenador_id')->where('status', 'pre_alocada')))
                ->latest()->take(5)->get();
        } elseif ($user->hasRole('Auxiliar')) {
            $meus = fn ($q) => $q->where('auxiliar_id', $user->id)->orWhereNull('auxiliar_id');

            $stats = [
                'aguardando' => LabReservation::whereIn('status', ['aprovada', 'aguardando_conferencia'])->where($meus)->count(),
                'ativas'     => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->where($meus)->count(),
                'concluidas' => LabReservation::whereIn('status', ['validada', 'concluida', 'finalizada'])->where('auxiliar_id', $user->id)->count(),
                'total'      => LabReservation::where('auxiliar_id', $user->id)->count(),
            ];
            $recent = LabReservation::with(['user', 'space'])
                ->whereIn('status', ['aprovada', 'aguardando_conferencia', 'em_execucao'])
                ->where($meus)
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

        $query = LabReservation::with(['user', 'space', 'materials', 'auxiliar', 'coordenador']);

        // Se o usuário NÃO for Admin, Coordenador ou Auxiliar, ele só vê as próprias reservas
        if (!$user->is_admin && !$user->hasRole('Coordenador') && !$user->hasRole('Auxiliar')) {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('space_id')) {
            $query->where('space_id', $request->space_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }

        $completedFilter = $request->get('completed_filter', 'ativas');
        if (! in_array($completedFilter, ['ativas', 'ocultas', 'todas'], true)) {
            $completedFilter = 'ativas';
        }

        $columns = [
            'pre_alocada'            => 'Solicitada',
            'aprovada'               => 'Aprovada',
            'em_execucao'            => 'Em Aula',
            'aguardando_conferencia' => 'Conferência',
            'aguardando_validacao'   => 'Validação',
            'concluida'              => 'Concluída',
        ];

        $oneWeekAgo = now()->subDays(7);
        $board = [];

        foreach ($columns as $status => $label) {
            $statusQuery = (clone $query);

            if ($status === 'concluida') {
                $statusQuery->whereIn('status', ['concluida', 'validada', 'finalizada']);

                if ($completedFilter === 'ativas') {
                    $statusQuery->where(function ($q) use ($oneWeekAgo) {
                        $q->where('updated_at', '>=', $oneWeekAgo);
                    });
                } elseif ($completedFilter === 'ocultas') {
                    $statusQuery->where(function ($q) use ($oneWeekAgo) {
                        $q->where('updated_at', '<', $oneWeekAgo);
                    });
                }
            } else {
                $statusQuery->where('status', $status);
            }

            $statusQuery->orderBy('reservation_date', 'asc')
                        ->orderBy('start_time', 'asc');

            $board[$status] = $statusQuery->get();
        }

        $spaces = Space::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('lab.reservations.index', [
            'board' => $board,
            'columns' => $columns,
            'spaces' => $spaces,
            'users' => $users,
            'selectedSpace' => $request->space_id,
            'selectedUser' => $request->user_id,
            'selectedDate' => $request->date,
            'selectedCompletedFilter' => $completedFilter,
        ]);
    }

    public function create()
    {
        $spaces        = Space::orderBy('name')->get();
        $materials     = Material::orderBy('name')->get();
        $coordenadores = User::role('Coordenador')->where('is_active', true)->orderBy('name')->get();
        return view('lab.reservations.create', compact('spaces', 'materials', 'coordenadores'));
    }

    public function store(Request $request)
    {
        $minDate = now()->addDays(2)->format('Y-m-d');

        $validated = $request->validate([
            'space_id'         => 'required|exists:spaces,id',
            'coordenador_id'   => ['required', 'exists:users,id', function ($attr, $val, $fail) {
                if (! User::find($val)?->hasRole('Coordenador')) {
                    $fail('O usuário selecionado não é um Coordenador.');
                }
            }],
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
            'coordenador_id.required'         => 'Selecione o coordenador responsável.',
        ]);

        $reservation = LabReservation::create([
            'user_id'          => auth()->id(),
            'space_id'         => $validated['space_id'],
            'coordenador_id'   => $validated['coordenador_id'],
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

        $reservation->load(['user', 'space', 'coordenador']);
        $title = 'Nova reserva aguardando aprovação';
        $body  = "{$reservation->user?->name} solicitou a reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')}.";
        $this->notifier()->notify([$reservation->coordenador], $reservation, $title, $body, auth()->user());

        return redirect()->route('lab.reservations.show', $reservation)
            ->with('success', 'Reserva criada com sucesso! Aguarde aprovação do coordenador.')
            ->with('print_pdf', true);
    }

    public function show(LabReservation $reservation)
    {
        abort_unless($reservation->isVisibleTo(auth()->user()), 403);

        $reservation->load(['user', 'space.auxiliar', 'auxiliar', 'coordenador', 'materials', 'images']);
        $auxiliaresDisponiveis = auth()->user()->auxiliaresParaAprovacao();

        return view('lab.reservations.show', compact('reservation', 'auxiliaresDisponiveis'));
    }

    // ── Coordenador / Admin: aprovar e encaminhar ao auxiliar ──
    public function approve(Request $request, LabReservation $reservation)
    {
        abort_unless($reservation->canBeActedOnByCoordenador(auth()->user()), 403);

        $request->validate([
            'auxiliar_id' => ['required', 'exists:users,id', function ($attr, $val, $fail) {
                $user = auth()->user();
                $ok = $user->is_admin
                    ? User::find($val)?->hasRole('Auxiliar')
                    : $user->auxiliaresVinculados()->where('is_active', true)->whereKey($val)->exists();
                if (! $ok) {
                    $fail('Selecione um auxiliar válido vinculado a você.');
                }
            }],
        ], [
            'auxiliar_id.required' => 'Selecione o auxiliar responsável.',
        ]);

        $reservation->update([
            'status'         => 'aprovada',
            'coordenador_id' => $reservation->coordenador_id ?? auth()->id(),
            'auxiliar_id'    => $request->auxiliar_id,
        ]);
        $reservation->loadMissing(['user', 'auxiliar']);

        $title = 'Reserva aprovada';
        $body  = "A reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')} foi aprovada pelo coordenador.";
        $this->notifier()->notify([$reservation->user, $reservation->auxiliar], $reservation, $title, $body, auth()->user());

        return back()->with('success', 'Reserva aprovada! O auxiliar foi notificado para preparar o laboratório.');
    }

    public function reject(LabReservation $reservation)
    {
        abort_unless($reservation->canBeActedOnByCoordenador(auth()->user()), 403);

        $reservation->update([
            'status'         => 'recusada',
            'coordenador_id' => $reservation->coordenador_id ?? auth()->id(),
        ]);
        return back()->with('success', 'Reserva recusada.');
    }

    // ── Auxiliar: entregar materiais + professor assina ──
    public function startClass(LabReservation $reservation)
    {
        $user = auth()->user();
        abort_unless(
            $user->is_admin
                || $reservation->user_id === $user->id
                || $reservation->auxiliar_id === $user->id
                || (is_null($reservation->auxiliar_id) && $user->hasRole('Auxiliar')),
            403
        );

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
        abort_unless($reservation->user_id === auth()->id() || auth()->user()->is_admin, 403);

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
        abort_unless($reservation->canBeFinalizedByAuxiliar(auth()->user()), 403);

        $request->validate([
            'auxiliar_obs' => 'required|string|min:5',
        ], [
            'auxiliar_obs.required' => 'Informe as observações da conferência.',
        ]);

        $data = [
            'auxiliar_obs'            => $request->auxiliar_obs,
            'auxiliar_id'             => $reservation->auxiliar_id ?? auth()->id(),
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
        abort_unless($reservation->canBeActedOnByCoordenador(auth()->user()), 403);

        $request->validate([
            'coordenador_obs' => 'nullable|string|max:2000',
        ]);

        $reservation->update([
            'status'          => 'validada',
            'validated_at'    => now(),
            'coordenador_obs' => $request->coordenador_obs,
            'coordenador_id'  => $reservation->coordenador_id ?? auth()->id(),
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
        $user = auth()->user();

        $reservations = LabReservation::with(['user', 'space'])
            ->whereIn('status', ['validada', 'concluida', 'finalizada'])
            ->when(! $user->is_admin, function ($query) use ($user) {
                if ($user->hasRole('Coordenador')) {
                    $query->where('coordenador_id', $user->id);
                } elseif ($user->hasRole('Auxiliar')) {
                    $query->where('auxiliar_id', $user->id);
                } else {
                    $query->where('user_id', $user->id);
                }
            })
            ->orderByRaw('COALESCE(finalized_at, validated_at) DESC')
            ->paginate(20);

        return view('lab.reservations.history', compact('reservations'));
    }

    public function generatePDF(LabReservation $reservation)
    {
        abort_unless($reservation->isVisibleTo(auth()->user()), 403);

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

    public function calendar()
    {
        $spaces = Space::orderBy('name')->get();
        return view('lab.reservations.calendar', compact('spaces'));
    }

    public function calendarEvents(Request $request)
    {
        $query = LabReservation::with(['space', 'user', 'materials'])
            ->whereNotIn('status', ['recusada']);

        if ($request->filled('space_id')) {
            $query->where('space_id', $request->space_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $statusLabels = [
            'pre_alocada' => 'Solicitada',
            'aprovada' => 'Aprovada',
            'em_execucao' => 'Em Aula',
            'aguardando_conferencia' => 'Conferência',
            'aguardando_validacao' => 'Validação',
            'validada' => 'Validada',
            'concluida' => 'Concluída',
            'finalizada' => 'Finalizada',
        ];

        $reservations = $query->get()->map(function ($r) use ($statusLabels) {
            $dateStr = $r->reservation_date instanceof \DateTimeInterface
                ? $r->reservation_date->format('Y-m-d')
                : substr((string)$r->reservation_date, 0, 10);
            $timeStr = $r->start_time ?? '08:00:00';
            $endTimeStr = $r->end_time ?? \Carbon\Carbon::parse($timeStr)->addHours(2)->format('H:i:s');

            $color = match($r->status) {
                'em_execucao' => '#27ae60',
                'aprovada' => '#2f80ed',
                'aguardando_conferencia', 'aguardando_validacao' => '#8b5cf6',
                'validada', 'concluida', 'finalizada' => '#56ccf2',
                default => '#f2994a',
            };

            $teacherName = $r->user->name ?? 'Docente';
            $spaceName = $r->space->name ?? 'Ambiente';

            return [
                'id'    => $r->id,
                'title' => $spaceName . ' • ' . $teacherName,
                'start' => $dateStr . 'T' . $timeStr,
                'end'   => $dateStr . 'T' . $endTimeStr,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'url'   => route('lab.reservations.show', $r->id),
                'extendedProps' => [
                    'id' => $r->id,
                    'spaceName' => $spaceName,
                    'teacherName' => $teacherName,
                    'statusLabel' => $statusLabels[$r->status] ?? ucfirst($r->status),
                    'statusColor' => $color,
                    'dateFormatted' => $r->reservation_date ? \Carbon\Carbon::parse($r->reservation_date)->format('d/m/Y') : '',
                    'timeFormatted' => substr($timeStr, 0, 5) . ' às ' . substr($endTimeStr, 0, 5),
                    'lessonPlan' => $r->description ?? '',
                    'materials' => $r->materials->map(fn($m) => [
                        'name' => $m->name,
                        'qty' => $m->pivot->quantity_used ?? $m->pivot->quantity ?? 1,
                    ]),
                    'showUrl' => route('lab.reservations.show', $r->id),
                    'pdfUrl' => route('lab.reservations.pdf', $r->id),
                ],
            ];
        });

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
