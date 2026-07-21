<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use App\Http\Resources\LabReservationResource;
use App\Mail\LabReservationFinalized;
use App\Models\LabReservation;
use App\Models\Material;
use App\Models\Space;
use App\Models\User;
use App\Services\PushNotificationService;
use App\Services\ReservationNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LabReservationController extends Controller
{
    private const RESOURCE_RELATIONS = [
        'user.teacher', 'space.auxiliar.teacher', 'auxiliar.teacher', 'coordenador.teacher', 'materials', 'images',
    ];

    public function dashboard(Request $request)
    {
        $user = $request->user();

        if ($user->is_admin) {
            $stats = [
                'spaces'    => Space::count(),
                'materials' => Material::count(),
                'pending'   => LabReservation::where('status', 'pre_alocada')->count(),
                'active'    => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
            ];
            $recent = LabReservation::with(self::RESOURCE_RELATIONS)->latest()->take(5)->get();
        } elseif ($user->hasRole('Coordenador')) {
            $stats = [
                'aguardando_aprovacao' => LabReservation::where('status', 'pre_alocada')->count(),
                'aguardando_validacao' => LabReservation::where('status', 'aguardando_validacao')->count(),
                'ativas'               => LabReservation::whereIn('status', ['aprovada', 'em_execucao', 'aguardando_conferencia'])->count(),
                'validadas'            => LabReservation::where('status', 'validada')->count(),
            ];
            $recent = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->latest()->take(5)->get();
        } elseif ($user->hasRole('Auxiliar')) {
            $stats = [
                'aguardando' => LabReservation::whereIn('status', ['aprovada', 'aguardando_conferencia'])->count(),
                'ativas'     => LabReservation::whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas' => LabReservation::whereIn('status', ['validada', 'concluida', 'finalizada'])->count(),
                'total'      => LabReservation::count(),
            ];
            $recent = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereIn('status', ['aprovada', 'aguardando_conferencia', 'em_execucao'])
                ->latest()->take(5)->get();
        } else {
            $stats = [
                'minhas'     => LabReservation::where('user_id', $user->id)->count(),
                'pendentes'  => LabReservation::where('user_id', $user->id)->where('status', 'pre_alocada')->count(),
                'ativas'     => LabReservation::where('user_id', $user->id)->whereIn('status', ['aprovada', 'em_execucao'])->count(),
                'concluidas' => LabReservation::where('user_id', $user->id)->whereIn('status', ['concluida', 'finalizada'])->count(),
            ];
            $recent = LabReservation::with(self::RESOURCE_RELATIONS)
                ->where('user_id', $user->id)
                ->latest()->take(5)->get();
        }

        return response()->json([
            'stats'  => $stats,
            'recent' => LabReservationResource::collection($recent),
        ]);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        $filters = $request->only(['status', 'space_id', 'data_inicio', 'data_fim', 'busca']);

        $applyFilters = function ($query) use ($filters) {
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
                    $sub->whereHas('space', fn ($s) => $s->where('name', 'like', "%{$q}%"))
                        ->orWhereHas('user', fn ($s) => $s->where('name', 'like', "%{$q}%"))
                        ->orWhere('description', 'like', "%{$q}%");
                });
            }
        };

        if ($user->is_admin || $user->hasRole('Coordenador')) {
            $pendentes = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereIn('status', ['pre_alocada', 'aguardando_validacao'])
                ->orderByRaw("CASE status WHEN 'aguardando_validacao' THEN 0 ELSE 1 END")
                ->latest()->get();

            $reservations = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereNotIn('status', ['validada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();
        } elseif ($user->hasRole('Auxiliar')) {
            $pendentes = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereIn('status', ['aprovada', 'aguardando_conferencia'])
                ->latest()->get();

            $reservations = LabReservation::with(self::RESOURCE_RELATIONS)
                ->whereNotIn('status', ['validada', 'recusada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();
        } else {
            $pendentes = [];
            $reservations = LabReservation::with(self::RESOURCE_RELATIONS)
                ->where('user_id', $user->id)
                ->whereNotIn('status', ['validada'])
                ->tap($applyFilters)
                ->latest()->paginate(20)->withQueryString();
        }

        return response()->json([
            'pendentes' => LabReservationResource::collection($pendentes),
            'reservations' => [
                'data'  => LabReservationResource::collection($reservations->items()),
                'meta'  => [
                    'current_page' => $reservations->currentPage(),
                    'last_page'    => $reservations->lastPage(),
                    'per_page'     => $reservations->perPage(),
                    'total'        => $reservations->total(),
                ],
                'links' => [
                    'first' => $reservations->url(1),
                    'last'  => $reservations->url($reservations->lastPage()),
                    'prev'  => $reservations->previousPageUrl(),
                    'next'  => $reservations->nextPageUrl(),
                ],
            ],
        ]);
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
            'user_id'          => $request->user()->id,
            'space_id'         => $validated['space_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'] ?? null,
            'description'      => $validated['description'],
            'status'           => 'pre_alocada',
        ]);

        $matIds = $request->input('mat_ids', []);
        $matQty = $request->input('mat_qty', []);

        foreach ($matIds as $matId) {
            $qty = max(1, (int) ($matQty[$matId] ?? 1));
            $reservation->materials()->attach($matId, [
                'quantity_requested' => $qty,
            ]);
        }

        $reservation->load(self::RESOURCE_RELATIONS);

        $title = 'Nova reserva aguardando aprovação';
        $body  = "{$reservation->user?->name} solicitou a reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')}.";
        $this->notifier()->notify(User::coordenadores()->get(), $reservation, $title, $body, $request->user());

        return (new LabReservationResource($reservation))
            ->additional(['message' => 'Reserva criada com sucesso! Aguarde aprovação do coordenador.'])
            ->response()->setStatusCode(201);
    }

    public function show(LabReservation $reservation)
    {
        $reservation->load(self::RESOURCE_RELATIONS);

        return new LabReservationResource($reservation);
    }

    public function approve(Request $request, LabReservation $reservation)
    {
        $reservation->update([
            'status'         => 'aprovada',
            'coordenador_id' => $request->user()->id,
        ]);
        $reservation->loadMissing(['user', 'space.auxiliar']);

        $title = 'Reserva aprovada';
        $body  = "A reserva de {$reservation->space?->name} para {$reservation->reservation_date->format('d/m/Y')} foi aprovada pelo coordenador.";
        $this->notifier()->notify([$reservation->user, $reservation->space?->auxiliar], $reservation, $title, $body, $request->user());

        return $this->respond($reservation, 'Reserva aprovada! O auxiliar foi notificado para preparar o laboratório.');
    }

    public function reject(Request $request, LabReservation $reservation)
    {
        $reservation->update([
            'status'         => 'recusada',
            'coordenador_id' => $request->user()->id,
        ]);

        return $this->respond($reservation, 'Reserva recusada.');
    }

    public function startClass(Request $request, LabReservation $reservation)
    {
        $reservation->update([
            'status'              => 'em_execucao',
            'professor_signed_at' => now(),
        ]);
        $reservation->loadMissing(['user', 'space']);

        $title = 'Materiais entregues, aula iniciada';
        $body  = "Os materiais da reserva em {$reservation->space?->name} foram entregues e a aula foi iniciada.";
        $recipients = [$reservation->user, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $reservation, $title, $body, $request->user());

        return $this->respond($reservation, 'Materiais entregues e checklist assinado. Boa aula!');
    }

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

        if ($reservation->auxiliar_released_at) {
            $data['status'] = 'aguardando_validacao';
        }

        $reservation->update($data);

        $fresh = $reservation->fresh();
        $msg = $fresh->status === 'aguardando_validacao'
            ? 'Observações registradas e atividade enviada ao coordenador para validação!'
            : 'Observações registradas! Aguardando o auxiliar liberar para enviar ao coordenador.';

        $fresh->loadMissing(['space.auxiliar', 'user']);
        $title = 'Observações da aula registradas';
        $body  = "{$fresh->user?->name} registrou as observações da reserva em {$fresh->space?->name}.";
        $recipients = [$fresh->space?->auxiliar, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $fresh, $title, $body, $request->user());

        return $this->respond($fresh, $msg);
    }

    public function auxiliarFinalize(Request $request, LabReservation $reservation)
    {
        $request->validate([
            'auxiliar_obs' => 'required|string|min:5',
        ], [
            'auxiliar_obs.required' => 'Informe as observações da conferência.',
        ]);

        $data = [
            'auxiliar_obs'             => $request->auxiliar_obs,
            'auxiliar_id'              => $request->user()->id,
            'auxiliar_released_at'     => now(),
            'confirmed_by_auxiliar_at' => now(),
        ];

        if ($reservation->professor_released_at) {
            $data['status'] = 'aguardando_validacao';
        } else {
            $data['status'] = 'em_execucao';
        }

        $reservation->update($data);
        $reservation->loadMissing(['auxiliar', 'user', 'space']);

        $msg = $data['status'] === 'aguardando_validacao'
            ? 'Conferência registrada. Reserva enviada ao coordenador para validação!'
            : 'Conferência registrada. Aguardando o professor registrar as observações.';

        $title = 'Conferência do auxiliar registrada';
        $body  = "{$reservation->auxiliar?->name} concluiu a conferência da reserva em {$reservation->space?->name}.";
        $recipients = [$reservation->user, ...User::coordenadores()->get()];
        $this->notifier()->notify($recipients, $reservation, $title, $body, $request->user());

        return $this->respond($reservation, $msg);
    }

    public function validateActivity(Request $request, LabReservation $reservation)
    {
        $request->validate([
            'coordenador_obs' => 'nullable|string|max:2000',
        ]);

        $reservation->update([
            'status'          => 'validada',
            'validated_at'    => now(),
            'coordenador_obs' => $request->coordenador_obs,
            'coordenador_id'  => $request->user()->id,
        ]);

        $reservation->loadMissing(self::RESOURCE_RELATIONS);

        try {
            if ($reservation->user?->email) {
                Mail::to($reservation->user->email)
                    ->send(new LabReservationFinalized($reservation, 'professor'));
            }
            if ($reservation->auxiliar?->email) {
                Mail::to($reservation->auxiliar->email)
                    ->send(new LabReservationFinalized($reservation, 'auxiliar'));
            }
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

        return $this->respond($reservation, 'Atividade validada e arquivada! Notificações enviadas ao professor e auxiliar.');
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

        return $this->respond($reservation, 'Documento enviado. Reserva concluída!');
    }

    public function uploadImage(Request $request, LabReservation $reservation)
    {
        $validated = $request->validate([
            'type'  => 'required|in:delivery,return',
            'photo' => 'required|image|max:5120',
        ]);

        $path = $request->file('photo')->store('reservations/images', 'public');

        $reservation->images()->create([
            'path' => $path,
            'type' => $validated['type'],
        ]);

        $msg = $validated['type'] === 'delivery'
            ? 'Foto de entrega registrada.'
            : 'Foto de devolução registrada.';

        return $this->respond($reservation, $msg);
    }

    public function history()
    {
        $reservations = LabReservation::with(self::RESOURCE_RELATIONS)
            ->whereIn('status', ['validada', 'concluida', 'finalizada'])
            ->orderByRaw('COALESCE(finalized_at, validated_at) DESC')
            ->paginate(20);

        return LabReservationResource::collection($reservations);
    }

    public function availability(Space $space)
    {
        $reservations = LabReservation::where('space_id', $space->id)
            ->whereNotIn('status', ['recusada', 'validada'])
            ->where('reservation_date', '>=', now()->startOfMonth())
            ->where('reservation_date', '<=', now()->addMonths(3)->endOfMonth())
            ->get(['id', 'reservation_date', 'start_time', 'end_time', 'status'])
            ->map(fn ($r) => [
                'date'     => $r->reservation_date->format('Y-m-d'),
                'start'    => substr($r->start_time, 0, 5),
                'end'      => $r->end_time ? substr($r->end_time, 0, 5) : null,
                'status'   => $r->status,
                'occupied' => true,
            ]);

        return response()->json($reservations);
    }

    public function calendarEvents()
    {
        $reservations = LabReservation::with(['space', 'user'])
            ->whereNotIn('status', ['recusada'])
            ->get()
            ->map(fn ($r) => [
                'id'    => $r->id,
                'title' => $r->space->name.' — '.($r->user->name ?? ''),
                'start' => $r->reservation_date->format('Y-m-d').'T'.$r->start_time,
                'end'   => $r->reservation_date->format('Y-m-d').'T'.($r->end_time ?? $r->start_time),
                'color' => match ($r->status) {
                    'pre_alocada' => '#9ca3af',
                    'aprovada'    => '#3b82f6',
                    'em_execucao' => '#f59e0b',
                    'concluida', 'finalizada' => '#22c55e',
                    'recusada'    => '#ef4444',
                    default       => '#6b7280',
                },
            ]);

        return response()->json($reservations);
    }

    private function respond(LabReservation $reservation, string $message)
    {
        $reservation->loadMissing(self::RESOURCE_RELATIONS);

        return (new LabReservationResource($reservation))
            ->additional(['message' => $message]);
    }

    private function pushService(): PushNotificationService
    {
        return app(PushNotificationService::class);
    }

    private function pushData(LabReservation $reservation): array
    {
        return [
            'type'            => 'reservation_update',
            'reservation_id'  => (string) $reservation->id,
        ];
    }

    private function notifier(): ReservationNotifier
    {
        return app(ReservationNotifier::class);
    }
}
