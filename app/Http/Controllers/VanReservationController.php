<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VanReservation;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VanReservationController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();
        $canViewAudit = $user->canViewVanAudit();

        $vehicles = Vehicle::where('is_active', true)->get();
        $primaryVehicle = $vehicles->first();

        $query = VanReservation::with(['user.department', 'vehicle', 'approver', 'completer']);

        if (!$canManage) {
            if ($request->get('tab') === 'todas') {
                $query->whereIn('status', ['aprovada', 'em_andamento']);
            } else {
                $query->where('user_id', $user->id);
            }
        } else {
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('purpose', 'like', "%{$search}%")
                    ->orWhere('destination', 'like', "%{$search}%")
                    ->orWhere('driver_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $reservations = $query->orderBy('departure_date', 'desc')
            ->orderBy('departure_time', 'desc')
            ->paginate(12)
            ->withQueryString();

        $pendingQuery = VanReservation::with(['user.department', 'vehicle'])
            ->where('status', 'pendente');

        if (!$canManage) {
            $pendingQuery->where('user_id', $user->id);
        }

        $pendingReservations = $pendingQuery->orderBy('departure_date', 'asc')->get();

        $upcomingTrips = VanReservation::with(['user', 'vehicle'])
            ->whereIn('status', ['aprovada', 'em_andamento'])
            ->where('departure_date', '>=', today()->subDays(1))
            ->orderBy('departure_date', 'asc')
            ->orderBy('departure_time', 'asc')
            ->take(10)
            ->get();

        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->toDateString();
        $endOfMonth = $now->copy()->endOfMonth()->toDateString();

        $stats = [
            'total_reservations' => VanReservation::count(),
            'pending_count' => VanReservation::where('status', 'pendente')->count(),
            'approved_count' => VanReservation::where('status', 'aprovada')->count(),
            'active_trips' => VanReservation::where('status', 'em_andamento')->count(),
            'completed_count' => VanReservation::where('status', 'concluida')->count(),
            'km_month' => (int) VanReservation::where('status', 'concluida')
                ->whereBetween('departure_date', [$startOfMonth, $endOfMonth])
                ->sum('total_km'),
            'van_current_km' => $primaryVehicle?->current_km ?? 0,
            'van_status' => $primaryVehicle?->status ?? 'disponivel',
        ];

        $users = $canManage ? User::orderBy('name')->get() : collect([$user]);

        return view('van_reservations.index', compact(
            'reservations',
            'pendingReservations',
            'upcomingTrips',
            'vehicles',
            'primaryVehicle',
            'stats',
            'users',
            'canManage',
            'canViewAudit'
        ));
    }

    public function create()
    {
        /** @var User $user */
        $user = Auth::user();
        $vehicles = Vehicle::where('is_active', true)->get();
        $canManage = $user->canManageVanReservations();
        $users = $canManage ? User::orderBy('name')->get() : collect([$user]);

        return view('van_reservations.create', compact('vehicles', 'users', 'canManage'));
    }

    public function store(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'purpose' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'departure_time' => ['required', 'date_format:H:i'],
            'return_date' => ['required', 'date', 'after_or_equal:departure_date'],
            'return_time' => ['required', 'date_format:H:i'],
            'passengers_count' => ['required', 'integer', 'min:1', 'max:50'],
            'passenger_list' => ['nullable', 'string', 'max:5000'],
            'driver_type' => ['required', 'string', 'in:servidor_habilitado,motorista_oficial,terceirizado'],
            'driver_name' => ['required', 'string', 'max:150'],
            'driver_cnh' => ['nullable', 'string', 'max:30'],
            'driver_phone' => ['nullable', 'string', 'max:30'],
            'checklist_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $targetUserId = ($canManage && !empty($validated['user_id'])) ? $validated['user_id'] : $user->id;

        $departureDateTime = Carbon::parse($validated['departure_date'] . ' ' . $validated['departure_time']);
        $hoursInAdvance = (int) now()->diffInHours($departureDateTime, false);
        $isWithin72h = $hoursInAdvance >= 72;

        $conflict = VanReservation::where('vehicle_id', $validated['vehicle_id'])
            ->whereIn('status', ['pendente', 'aprovada', 'em_andamento'])
            ->where(function ($q) use ($validated) {
                $q->whereDate('departure_date', '<=', $validated['return_date'])
                    ->whereDate('return_date', '>=', $validated['departure_date']);
            })
            ->first();

        if ($conflict) {
            return redirect()->back()
                ->withInput()
                ->with('error', "A Van Escolar já possui uma reserva ({$conflict->purpose} - Status: {$conflict->status_label}) no período de {$conflict->departure_date->format('d/m/Y')} a {$conflict->return_date->format('d/m/Y')}. Por favor, escolha outra data/horário.");
        }

        $reservation = VanReservation::create([
            'user_id' => $targetUserId,
            'vehicle_id' => $validated['vehicle_id'],
            'purpose' => $validated['purpose'],
            'destination' => $validated['destination'],
            'departure_date' => $validated['departure_date'],
            'departure_time' => $validated['departure_time'],
            'return_date' => $validated['return_date'],
            'return_time' => $validated['return_time'],
            'passengers_count' => $validated['passengers_count'],
            'passenger_list' => $validated['passenger_list'] ?? null,
            'driver_type' => $validated['driver_type'],
            'driver_name' => $validated['driver_name'],
            'driver_cnh' => $validated['driver_cnh'] ?? null,
            'driver_phone' => $validated['driver_phone'] ?? null,
            'is_within_72h_deadline' => $isWithin72h,
            'hours_in_advance' => $hoursInAdvance,
            'checklist_notes' => $validated['checklist_notes'] ?? null,
            'status' => 'pendente',
        ]);

        $solicitanteName = User::find($targetUserId)?->name ?? $user->name;
        $prazoAviso = $isWithin72h ? "✅ Dentro do prazo de antecedência ({$hoursInAdvance}h)" : "⚠️ Em caráter de urgência (< 72h: {$hoursInAdvance}h)";

        $reservation->recordAudit(
            'solicitacao',
            "Solicitação de reserva da Van criada para {$reservation->destination} ({$reservation->departure_date->format('d/m/Y')} {$reservation->departure_time}). {$prazoAviso}",
            [
                'solicitante' => $solicitanteName,
                'destino' => $reservation->destination,
                'motivo' => $reservation->purpose,
                'saida' => $reservation->departure_date->format('d/m/Y') . ' ' . $reservation->departure_time,
                'retorno' => $reservation->return_date->format('d/m/Y') . ' ' . $reservation->return_time,
                'passageiros' => $reservation->passengers_count,
                'condutor' => $reservation->driver_name,
                'antecedencia_horas' => $hoursInAdvance,
                'dentro_72h' => $isWithin72h,
            ],
            $user->id
        );

        $msg = $isWithin72h
            ? 'Reserva da Van Escolar solicitada com sucesso! Aguarde a liberação da Diretora de Serviços.'
            : 'Reserva da Van Escolar solicitada com aviso de urgência (< 72h de antecedência). Aguarde a análise da Diretora de Serviços.';

        return redirect()->route('van-reservations.show', $reservation->id)
            ->with('success', $msg);
    }

    public function show(VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();
        $canViewAudit = $user->canViewVanAudit();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Acesso restrito. Somente o solicitante da viagem e a Diretoria de Serviços podem visualizar esta reserva.');
        }

        $relations = ['user.department', 'vehicle', 'approver', 'completer'];
        if ($canViewAudit) {
            $relations[] = 'audits.user';
        }

        $vanReservation->load($relations);

        return view('van_reservations.show', compact('vanReservation', 'canManage', 'canViewAudit'));
    }

    public function edit(VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Apenas o solicitante ou a Diretoria de Serviços podem editar esta reserva.');
        }

        if (!$canManage && in_array($vanReservation->status, ['concluida', 'cancelada'])) {
            return redirect()->route('van-reservations.show', $vanReservation->id)
                ->with('error', 'Reservas concluídas ou canceladas não podem ser editadas.');
        }

        $vehicles = Vehicle::where('is_active', true)->get();
        $users = $canManage ? User::orderBy('name')->get() : collect([$user]);

        return view('van_reservations.edit', compact('vanReservation', 'vehicles', 'users', 'canManage'));
    }

    public function update(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Apenas o solicitante ou a Diretoria de Serviços podem editar esta reserva.');
        }

        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'purpose' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'departure_date' => ['required', 'date'],
            'departure_time' => ['required', 'date_format:H:i'],
            'return_date' => ['required', 'date', 'after_or_equal:departure_date'],
            'return_time' => ['required', 'date_format:H:i'],
            'passengers_count' => ['required', 'integer', 'min:1', 'max:50'],
            'passenger_list' => ['nullable', 'string', 'max:5000'],
            'driver_type' => ['required', 'string', 'in:servidor_habilitado,motorista_oficial,terceirizado'],
            'driver_name' => ['required', 'string', 'max:150'],
            'driver_cnh' => ['nullable', 'string', 'max:30'],
            'driver_phone' => ['nullable', 'string', 'max:30'],
            'checklist_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $oldData = $vanReservation->only(['purpose', 'destination', 'departure_date', 'return_date', 'driver_name', 'passengers_count']);

        $vanReservation->update($validated);

        $vanReservation->recordAudit(
            'edicao',
            "Reserva da Van atualizada por {$user->name}.",
            [
                'anterior' => $oldData,
                'novo' => $vanReservation->only(['purpose', 'destination', 'departure_date', 'return_date', 'driver_name', 'passengers_count']),
            ],
            $user->id
        );

        return redirect()->route('van-reservations.show', $vanReservation->id)
            ->with('success', 'Reserva atualizada com sucesso!');
    }

    public function approve(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageVanReservations()) {
            abort(403, 'Apenas a Diretora de Serviços pode liberar e autorizar reservas da Van Escolar.');
        }

        $validated = $request->validate([
            'director_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $vanReservation->update([
            'status' => 'aprovada',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'director_notes' => $validated['director_notes'] ?? null,
        ]);

        $vanReservation->recordAudit(
            'liberacao_aprovacao',
            "Reserva da Van liberada e autorizada pela Diretora de Serviços ({$user->name}).",
            [
                'aprovado_por' => $user->name,
                'data_aprovacao' => now()->format('d/m/Y H:i:s'),
                'parecer_diretoria' => $validated['director_notes'] ?? 'Autorizado sem ressalvas.',
            ],
            $user->id
        );

        return redirect()->back()->with('success', 'Reserva da Van Escolar liberada e autorizada com sucesso!');
    }

    public function reject(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        if (!$user->canManageVanReservations()) {
            abort(403, 'Apenas a Diretora de Serviços pode recusar reservas da Van Escolar.');
        }

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:2000'],
        ]);

        $vanReservation->update([
            'status' => 'rejeitada',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        $vanReservation->recordAudit(
            'rejeicao',
            "Solicitação de reserva recusada pela Diretora de Serviços ({$user->name}). Motivo: {$validated['rejection_reason']}",
            [
                'avaliador' => $user->name,
                'motivo' => $validated['rejection_reason'],
            ],
            $user->id
        );

        return redirect()->back()->with('success', 'Solicitação de reserva da Van foi recusada.');
    }

    public function startTrip(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Apenas o solicitante ou a Diretoria de Serviços podem registrar a saída da Van.');
        }

        if ($vanReservation->status !== 'aprovada') {
            return redirect()->back()->with('error', 'Apenas viagens liberadas e aprovadas podem iniciar.');
        }

        $currentKm = $vanReservation->vehicle?->current_km ?? 0;

        $validated = $request->validate([
            'initial_km' => ['required', 'integer', 'min:' . $currentKm],
            'fuel_level_departure' => ['nullable', 'string', 'max:20'],
            'checklist_notes' => ['nullable', 'string', 'max:2000'],
            'initial_km_photo' => ['nullable', 'file', 'image', 'max:5120'],
        ]);

        $photoPath = null;
        if ($request->hasFile('initial_km_photo')) {
            $photoPath = $request->file('initial_km_photo')->store('van_photos', 'public');
        }

        $vanReservation->update([
            'initial_km' => $validated['initial_km'],
            'fuel_level_departure' => $validated['fuel_level_departure'] ?? 'Cheio',
            'checklist_notes' => $validated['checklist_notes'] ?? $vanReservation->checklist_notes,
            'initial_km_photo' => $photoPath ?? $vanReservation->initial_km_photo,
            'status' => 'em_andamento',
        ]);

        if ($vanReservation->vehicle) {
            $vanReservation->vehicle->update([
                'status' => 'em_viagem',
                'current_km' => $validated['initial_km'],
            ]);
        }

        $vanReservation->recordAudit(
            'saida_km',
            "Saída da Van registrada com KM Inicial de {$validated['initial_km']} km por {$user->name}.",
            [
                'km_inicial' => $validated['initial_km'],
                'combustivel_saida' => $validated['fuel_level_departure'] ?? 'Cheio',
                'registrado_por' => $user->name,
            ],
            $user->id
        );

        return redirect()->back()->with('success', "Saída registrada com sucesso! Hodômetro Inicial: {$validated['initial_km']} km. Boa viagem!");
    }

    public function finishTrip(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Apenas o solicitante ou a Diretoria de Serviços podem registrar o retorno da Van.');
        }

        if ($vanReservation->status !== 'em_andamento') {
            return redirect()->back()->with('error', 'Apenas viagens em andamento podem ser finalizadas.');
        }

        $initialKm = $vanReservation->initial_km ?? 0;

        $validated = $request->validate([
            'final_km' => ['required', 'integer', 'min:' . $initialKm],
            'fuel_level_return' => ['nullable', 'string', 'max:20'],
            'checklist_notes' => ['nullable', 'string', 'max:2000'],
            'final_km_photo' => ['nullable', 'file', 'image', 'max:5120'],
        ]);

        $photoPath = null;
        if ($request->hasFile('final_km_photo')) {
            $photoPath = $request->file('final_km_photo')->store('van_photos', 'public');
        }

        $totalKm = max(0, $validated['final_km'] - $initialKm);

        $vanReservation->update([
            'final_km' => $validated['final_km'],
            'total_km' => $totalKm,
            'fuel_level_return' => $validated['fuel_level_return'] ?? null,
            'checklist_notes' => $validated['checklist_notes'] ?? $vanReservation->checklist_notes,
            'final_km_photo' => $photoPath ?? $vanReservation->final_km_photo,
            'completed_by' => $user->id,
            'completed_at' => now(),
            'status' => 'concluida',
        ]);

        if ($vanReservation->vehicle) {
            $vanReservation->vehicle->update([
                'current_km' => $validated['final_km'],
                'status' => 'disponivel',
            ]);
        }

        $vanReservation->recordAudit(
            'retorno_km',
            "Retorno da Van registrado com KM Final de {$validated['final_km']} km (Distância percorrida: {$totalKm} km) por {$user->name}.",
            [
                'km_inicial' => $initialKm,
                'km_final' => $validated['final_km'],
                'total_km_percorrido' => $totalKm,
                'combustivel_retorno' => $validated['fuel_level_return'] ?? 'N/A',
                'finalizado_por' => $user->name,
            ],
            $user->id
        );

        return redirect()->back()->with('success', "Viagem concluída com sucesso! Quilometragem Final: {$validated['final_km']} km (Total percorrido: {$totalKm} km). O veículo está novamente disponível.");
    }

    public function cancel(Request $request, VanReservation $vanReservation)
    {
        /** @var User $user */
        $user = Auth::user();
        $canManage = $user->canManageVanReservations();

        if (!$canManage && $vanReservation->user_id !== $user->id) {
            abort(403, 'Apenas o solicitante ou a Diretoria de Serviços podem cancelar esta reserva.');
        }

        if (in_array($vanReservation->status, ['concluida', 'cancelada'])) {
            return redirect()->back()->with('error', 'Esta reserva já foi finalizada ou cancelada.');
        }

        $vanReservation->update([
            'status' => 'cancelada',
        ]);

        if ($vanReservation->vehicle && $vanReservation->vehicle->status === 'em_viagem') {
            $vanReservation->vehicle->update(['status' => 'disponivel']);
        }

        $vanReservation->recordAudit(
            'cancelamento',
            "Reserva cancelada por {$user->name}.",
            ['cancelado_por' => $user->name],
            $user->id
        );

        return redirect()->route('van-reservations.index')->with('success', 'Reserva cancelada com sucesso.');
    }
}

