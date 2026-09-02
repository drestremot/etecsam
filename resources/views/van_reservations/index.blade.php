@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1 text-xs font-extrabold text-blue-800">
                    🚐 VanTec • Gestão de Frotas & Transporte Escolar
                </span>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mt-2">
                    Reserva da Van Escolar
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">
                    Controle de viagens, solicitações pedagógicas com 72h de antecedência, liberação pela Diretora de Serviços e registro de hodômetro (KM).
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a
                    href="{{ route('van-reservations.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Nova Reserva da Van</span>
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-xs font-semibold text-emerald-800 shadow-sm flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-semibold text-red-800 shadow-sm flex items-center gap-2">
                <svg class="h-5 w-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Section 1: Regras do Transporte Escolar & 72 Horas -->
        <div class="rounded-2xl border border-blue-200 bg-blue-50/50 p-4 sm:p-5 shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-600 text-white font-bold text-xs">
                    i
                </span>
                <h2 class="text-xs font-bold uppercase tracking-wider text-blue-900">
                    Regras e Diretrizes para Utilização da Van Escolar
                </h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-blue-900/90 mt-3">
                <div class="bg-white/80 rounded-xl p-3 border border-blue-100 shadow-2xs">
                    <span class="font-bold text-blue-800 block mb-1">⏱️ Antecedência Mínima de 72h</span>
                    A solicitação deve ser feita no mínimo 72 horas antes da viagem para análise do itinerário e programação institucional.
                </div>
                <div class="bg-white/80 rounded-xl p-3 border border-blue-100 shadow-2xs">
                    <span class="font-bold text-blue-800 block mb-1">✍️ Liberação pela Diretora de Serviços</span>
                    A reserva só é confirmada após autorização expressa da Diretoria de Serviços no sistema.
                </div>
                <div class="bg-white/80 rounded-xl p-3 border border-blue-100 shadow-2xs">
                    <span class="font-bold text-blue-800 block mb-1">🚗 Hodômetro Obrigatório (KM Inicial & Final)</span>
                    O condutor/solicitante deve registrar a quilometragem exata na saída e no retorno do veículo.
                </div>
            </div>
        </div>

        <!-- Section 2: KPIs & Indicadores -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-2xs border-t-4 border-t-blue-600">
                <div class="flex items-center justify-between text-gray-400 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider">Total de Viagens</span>
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ $stats['total_reservations'] }}</div>
                <p class="text-[10px] text-gray-500 mt-1">{{ $stats['completed_count'] }} concluídas</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-2xs border-t-4 border-t-amber-500">
                <div class="flex items-center justify-between text-gray-400 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider">Aguardando Liberação</span>
                    <svg class="h-4 w-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-amber-600">{{ $stats['pending_count'] }}</div>
                <p class="text-[10px] text-amber-700 mt-1">Pela Diretora de Serviços</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-2xs border-t-4 border-t-emerald-600">
                <div class="flex items-center justify-between text-gray-400 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider">KM Rodados no Mês</span>
                    <svg class="h-4 w-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                </div>
                <div class="text-xl sm:text-2xl font-bold text-emerald-700">{{ number_format($stats['km_month'], 0, ',', '.') }} <span class="text-xs font-semibold text-gray-500">km</span></div>
                <p class="text-[10px] text-gray-500 mt-1">Hodômetro atual: {{ number_format($stats['van_current_km'], 0, ',', '.') }} km</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-2xs border-t-4 border-t-purple-600">
                <div class="flex items-center justify-between text-gray-400 mb-1">
                    <span class="text-[10px] font-bold uppercase tracking-wider">Status da Van</span>
                    <span class="inline-flex h-2 w-2 rounded-full {{ $stats['active_trips'] > 0 ? 'bg-blue-500 animate-pulse' : 'bg-emerald-500' }}"></span>
                </div>
                <div class="text-lg font-bold text-gray-900 truncate">
                    @if($stats['active_trips'] > 0)
                        <span class="text-blue-600">Em Viagem ({{ $stats['active_trips'] }})</span>
                    @else
                        <span class="text-emerald-600">Disponível</span>
                    @endif
                </div>
                <p class="text-[10px] text-gray-500 mt-1">{{ $primaryVehicle?->name ?? 'Van Oficial Etec' }} ({{ $primaryVehicle?->plate ?? 'ETC-2026' }})</p>
            </div>
        </div>

        <!-- Section 3: Solicitações Aguardando Liberação da Diretora de Serviços -->
        @if($pendingReservations->count() > 0)
            <div class="rounded-2xl border border-amber-300 bg-amber-50/40 p-4 sm:p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-amber-200/60 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex h-3 w-3 rounded-full bg-amber-500 animate-pulse"></span>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-amber-900">
                            Solicitações de Reserva Pendentes de Liberação ({{ $pendingReservations->count() }})
                        </h2>
                    </div>
                    @if($canManage)
                        <span class="text-[11px] font-bold text-amber-800 bg-amber-200/80 px-2.5 py-0.5 rounded-full">
                            Ação da Diretora de Serviços
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($pendingReservations as $req)
                        <div class="rounded-xl border border-amber-200 bg-white p-3.5 shadow-2xs flex flex-col justify-between space-y-3">
                            <div>
                                <div class="flex items-center justify-between gap-1 mb-1">
                                    <span class="text-[10px] font-bold text-gray-400">#{{ $req->id }} • {{ $req->created_at->format('d/m/Y H:i') }}</span>
                                    @if($req->is_within_72h_deadline)
                                        <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-1.5 py-0.5 text-[9.5px] font-bold text-emerald-800 border border-emerald-200">
                                            ✅ ≥ 72h ({{ $req->hours_in_advance }}h)
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded bg-red-50 px-1.5 py-0.5 text-[9.5px] font-bold text-red-800 border border-red-200" title="Solicitado com menos de 72h">
                                            ⚠️ Urgência (< 72h: {{ $req->hours_in_advance }}h)
                                        </span>
                                    @endif
                                </div>
                                <h3 class="text-xs font-bold text-gray-900">{{ $req->purpose }}</h3>
                                <p class="text-[11px] text-gray-600 mt-0.5 font-medium">📍 Destino: {{ $req->destination }}</p>

                                <div class="mt-2 text-[10.5px] text-gray-500 space-y-0.5 bg-gray-50 p-2 rounded-lg">
                                    <div>👤 <strong>Solicitante:</strong> {{ $req->user->name }} ({{ $req->user->department?->name ?? 'Etec' }})</div>
                                    <div>📅 <strong>Saída:</strong> {{ $req->departure_date->format('d/m/Y') }} às {{ $req->departure_time }}</div>
                                    <div>🔄 <strong>Retorno:</strong> {{ $req->return_date->format('d/m/Y') }} às {{ $req->return_time }}</div>
                                    <div>👥 <strong>Passageiros:</strong> {{ $req->passengers_count }} pessoas • Condutor: {{ $req->driver_name }}</div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-2 pt-2 border-t border-gray-100">
                                <a href="{{ route('van-reservations.show', $req->id) }}" class="text-[11px] font-bold text-blue-600 hover:underline">
                                    Ver Detalhes &rarr;
                                </a>

                                @if($canManage)
                                    <div class="flex items-center gap-1.5">
                                        <!-- Botão Rápido de Aprovação / Liberação -->
                                        <form action="{{ route('van-reservations.approve', $req->id) }}" method="POST" onsubmit="return confirm('Liberar e autorizar a saída da Van para {{ $req->destination }} em {{ $req->departure_date->format('d/m/Y') }}?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-[11px] font-bold text-white hover:bg-emerald-500 shadow-2xs transition">
                                                Liberar Van
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Section 4: Próximas Viagens Agendadas (Visão Geral de Disponibilidade) -->
        @if($upcomingTrips->count() > 0)
            <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-gray-100 pb-2.5">
                    <div class="flex items-center gap-2">
                        <span class="text-sm">🗓️</span>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-800">
                            Agenda da Van Escolar (Próximas Viagens Confirmadas)
                        </h2>
                    </div>
                    <span class="text-[10px] text-gray-400">Consulte os horários ocupados para planejar sua reserva</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($upcomingTrips as $trip)
                        <div class="rounded-xl border border-gray-200 bg-white p-3 shadow-2xs hover:border-blue-300 transition">
                            <div class="flex items-center justify-between mb-1.5">
                                <span class="inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-[9px] font-semibold uppercase {{ $trip->status_badge_color }}">
                                    {{ $trip->status_label }}
                                </span>
                                <span class="text-[10px] font-bold text-gray-500">{{ $trip->departure_date->format('d/m/Y') }}</span>
                            </div>
                            <h4 class="text-xs font-bold text-gray-900 truncate">{{ $trip->purpose }}</h4>
                            <p class="text-[11px] text-blue-700 font-semibold truncate">📍 {{ $trip->destination }}</p>
                            <div class="text-[10px] text-gray-500 mt-1 flex justify-between">
                                <span>⏰ {{ $trip->departure_time }} até {{ $trip->return_time }}</span>
                                <span>👤 {{ $trip->user->name }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Section 5: Extrato de Reservas com Filtros -->
        <div class="rounded-2xl border border-gray-300 bg-white p-4 sm:p-5 shadow-sm space-y-4">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700 font-bold text-xs">
                        🚐
                    </span>
                    <div>
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-900">
                            Histórico e Extrato de Reservas da Van
                        </h2>
                        <p class="text-[10.5px] text-gray-500">
                            {{ $canManage ? 'Todas as solicitações institucionais de transporte' : 'Minhas reservas solicitadas e viagens agendadas' }}
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <form method="GET" action="{{ route('van-reservations.index') }}" class="flex flex-wrap items-center gap-2">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Buscar destino, motivo..."
                        class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-800 shadow-2xs focus:border-blue-500"
                    />

                    @if($canManage)
                        <select name="user_id" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs">
                            <option value="">Todos os Solicitantes</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <select name="status" class="rounded-xl border border-gray-300 bg-white px-3 py-1.5 text-xs text-gray-700 shadow-2xs">
                        <option value="">Todos os Status</option>
                        <option value="pendente" {{ request('status') === 'pendente' ? 'selected' : '' }}>Aguardando Liberação</option>
                        <option value="aprovada" {{ request('status') === 'aprovada' ? 'selected' : '' }}>Liberada / Aprovada</option>
                        <option value="em_andamento" {{ request('status') === 'em_andamento' ? 'selected' : '' }}>Em Viagem</option>
                        <option value="concluida" {{ request('status') === 'concluida' ? 'selected' : '' }}>Concluída</option>
                        <option value="rejeitada" {{ request('status') === 'rejeitada' ? 'selected' : '' }}>Recusada</option>
                        <option value="cancelada" {{ request('status') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>

                    <button type="submit" class="rounded-xl bg-gray-800 px-3 py-1.5 text-xs font-semibold text-white shadow-2xs hover:bg-gray-700">
                        Filtrar
                    </button>
                    @if(request()->hasAny(['search', 'user_id', 'status']))
                        <a href="{{ route('van-reservations.index') }}" class="text-xs text-gray-500 hover:text-gray-800 underline">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            <!-- Table of Reservations -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-gray-700">
                    <thead class="bg-gray-50/80 text-[10.5px] uppercase tracking-wider text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="py-3 px-3"># ID</th>
                            <th class="py-3 px-3">Solicitante</th>
                            <th class="py-3 px-3">Motivo & Destino</th>
                            <th class="py-3 px-3">Período de Viagem</th>
                            <th class="py-3 px-3">Passageiros & Condutor</th>
                            <th class="py-3 px-3">72h</th>
                            <th class="py-3 px-3">Hodômetro (KM)</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-medium">
                        @forelse($reservations as $res)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="py-3 px-3 text-gray-400 font-bold">#{{ $res->id }}</td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-gray-900">{{ $res->user->name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $res->user->department?->name ?? 'Geral' }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-gray-900">{{ $res->purpose }}</div>
                                    <div class="text-[10px] text-blue-700 font-semibold">📍 {{ $res->destination }}</div>
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    <div class="font-bold text-gray-900">🛫 {{ $res->departure_date->format('d/m/Y') }} às {{ $res->departure_time }}</div>
                                    <div class="text-[10px] text-gray-500">🛬 {{ $res->return_date->format('d/m/Y') }} às {{ $res->return_time }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div>👥 {{ $res->passengers_count }} passageiros</div>
                                    <div class="text-[10px] text-gray-500">🚗 {{ $res->driver_name }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    @if($res->is_within_72h_deadline)
                                        <span class="inline-flex items-center gap-1 rounded bg-emerald-50 px-1.5 py-0.5 text-[9.5px] font-bold text-emerald-800 border border-emerald-200">
                                            ✅ ≥ 72h
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded bg-amber-50 px-1.5 py-0.5 text-[9.5px] font-bold text-amber-800 border border-amber-200" title="Solicitado com menos de 72h de antecedência">
                                            ⚠️ &lt; 72h
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($res->status === 'concluida')
                                        <div class="font-bold text-emerald-700">🏁 {{ number_format($res->total_km, 0, ',', '.') }} km</div>
                                        <div class="text-[9.5px] text-gray-400">{{ number_format($res->initial_km, 0, ',', '.') }} &rarr; {{ number_format($res->final_km, 0, ',', '.') }} km</div>
                                    @elseif($res->status === 'em_andamento')
                                        <div class="font-bold text-blue-600">Saída: {{ number_format($res->initial_km, 0, ',', '.') }} km</div>
                                        <div class="text-[9.5px] text-amber-600">Em trânsito...</div>
                                    @else
                                        <span class="text-gray-400 text-[10px] italic">Aguardando saída</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-[10px] font-semibold uppercase {{ $res->status_badge_color }}">
                                        {{ $res->status_label }}
                                    </span>
                                    @if($res->approved_by && $res->approver)
                                        <div class="text-[9px] text-gray-400 mt-0.5">
                                            Liberado por {{ $res->approver->name }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-right whitespace-nowrap">
                                    <a
                                        href="{{ route('van-reservations.show', $res->id) }}"
                                        class="inline-flex items-center gap-1 rounded-lg bg-gray-100 hover:bg-gray-200 px-2.5 py-1 text-[11px] font-bold text-gray-700 transition"
                                    >
                                        Visualizar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="py-8 text-center text-gray-400 italic">
                                    Nenhuma reserva de Van encontrada para os filtros selecionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
