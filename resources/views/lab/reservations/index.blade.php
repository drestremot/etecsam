@extends('layouts.operational')

@section('content')
@php
    $teacherColors = [
        '#2563eb', '#7c3aed', '#ea580c', '#16a34a', '#dc2626', '#0891b2', '#e11d48', '#65a30d', '#f59e0b', '#0f766e'
    ];

    $columnLabels = [
        'pre_alocada' => 'Solicitada',
        'aprovada' => 'Aprovada',
        'em_execucao' => 'Em Aula',
        'aguardando_conferencia' => 'Conferência',
        'aguardando_validacao' => 'Validação',
        'concluida' => 'Concluída',
    ];

    $statusColors = [
        'pre_alocada' => '#f2994a',
        'aprovada' => '#2f80ed',
        'em_execucao' => '#27ae60',
        'aguardando_conferencia' => '#8b5cf6',
        'aguardando_validacao' => '#06b6d4',
        'concluida' => '#56ccf2',
    ];
@endphp

<style>
    .kanban-board-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 1.5rem;
    }

    .kanban-board {
        display: flex;
        gap: 1rem;
        width: max-content;
        min-width: 100%;
    }

    .kanban-column {
        flex: 1 1 0;
        min-width: 290px;
        max-width: 360px;
        min-height: 560px;
        background: #dfe1e5;
        border-radius: 1.1rem;
        padding: 0.85rem;
        box-shadow: inset 0 0 0 1px rgba(148, 163, 184, 0.2);
        transition: all 0.2s ease-in-out;
    }

    @media (min-width: 1920px) {
        .kanban-board {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            width: 100%;
        }
        .kanban-column {
            max-width: none;
            min-width: 0;
        }
    }

    .kanban-column .task-list {
        min-height: 160px;
    }

    .reservation-card {
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }

    .reservation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px -2px rgba(15, 23, 42, 0.12), 0 3px 6px -2px rgba(15, 23, 42, 0.08);
    }

    .empty-state {
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        color: #5b6472;
        border-style: dashed;
        border-radius: 0.85rem;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-7 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-5">
        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

        <!-- Session Flash Alerts -->
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-red-600 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Floating Action Button (Mobile Only) -->
        <a href="{{ route('lab.reservations.create') }}" class="lg:hidden fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl transition active:scale-95 hover:bg-blue-500 focus:outline-none" title="Nova Reserva">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Reservas de Laboratórios</span>
                    <span class="rounded-xl bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 normal-case tracking-normal">Fluxo Kanban</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Acompanhamento e fluxo de aulas e materiais didáticos em tempo real</p>
            </div>

            <div class="hidden sm:flex flex-wrap items-center gap-3">
                <a href="{{ route('lab.reservations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Nova Reserva</span>
                </a>
                <a href="{{ route('lab.reservations.calendar') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Mapa de Laboratórios</span>
                </a>
                <a href="{{ route('lab.reservations.history') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-teal-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Histórico Concluídas</span>
                </a>
            </div>
        </div>

        <!-- Mobile Column Navigation Tabs -->
        <div class="2xl:hidden flex items-center gap-2 overflow-x-auto pb-1.5 -mx-1 px-1 no-scrollbar">
            @foreach($columns as $status => $label)
                @php
                    $tabColor = $statusColors[$status] ?? '#94a3b8';
                @endphp
                <button
                    type="button"
                    class="mobile-col-tab flex-shrink-0 inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs font-bold text-gray-700 shadow-sm active:bg-gray-100 transition"
                    data-target-status="{{ $status }}"
                >
                    <span class="inline-block h-2.5 w-2.5 rounded-full" style="background-color: {{ $tabColor }};"></span>
                    <span>{{ $columnLabels[$status] ?? $label }}</span>
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-bold text-gray-600">{{ $board[$status]->count() }}</span>
                </button>
            @endforeach
        </div>

        <!-- Filter & Search Bar with Mobile Accordion Toggle -->
        <div class="rounded-2xl border border-gray-300 bg-white/75 p-4 shadow-sm backdrop-blur-sm">
            <div class="flex items-center justify-between gap-3 cursor-pointer sm:cursor-default" id="toggleFilterBtn">
                <div class="flex items-center gap-2.5">
                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    <span class="text-xs font-semibold text-gray-800 uppercase tracking-wide">Filtros & Busca Inteligente</span>
                    @if(request('space_id') || request('user_id') || request('date') || (request('completed_filter') && request('completed_filter') !== 'ativas'))
                        <span class="rounded-full bg-blue-600 px-2 py-0.5 text-[10px] font-bold text-white">Filtros Ativos</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('lab.reservations.calendar') }}" class="sm:hidden text-xs font-bold text-blue-700 underline mr-1">Mapa</a>
                    <button type="button" class="sm:hidden text-gray-500 hover:text-gray-800">
                        <svg id="filterChevron" class="h-4 w-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Real-time Live Search Input (Always Visible) -->
            <div class="mt-3 flex items-center gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input
                        type="text"
                        id="reservationSearchInput"
                        placeholder="Busca em tempo real (docente, laboratório, material, plano ou #ID)..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-300 bg-white text-xs sm:text-sm text-gray-800 shadow-2xs focus:outline-none focus:ring-2 focus:ring-blue-400"
                    />
                </div>
                <span id="searchMatchCount" class="text-xs text-gray-600 font-bold hidden bg-gray-100 px-2.5 py-1.5 rounded-lg border border-gray-200"></span>
            </div>

            <!-- Expandable Server Filters -->
            <form id="filterFormContent" method="GET" class="hidden sm:grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-6 mt-3.5 pt-3.5 border-t border-gray-200">
                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Laboratório</label>
                    <select name="space_id" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Todos os laboratórios</option>
                        @foreach($spaces as $space)
                            <option value="{{ $space->id }}" {{ request('space_id') == $space->id ? 'selected' : '' }}>{{ $space->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Docente</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">Todos os docentes</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Data da Reserva</label>
                    <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Concluídas</label>
                    <select name="completed_filter" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="ativas" {{ ($selectedCompletedFilter ?? 'ativas') === 'ativas' ? 'selected' : '' }}>Ativas (≤ 7 dias)</option>
                        <option value="ocultas" {{ ($selectedCompletedFilter ?? '') === 'ocultas' ? 'selected' : '' }}>Ocultas (> 7 dias)</option>
                        <option value="todas" {{ ($selectedCompletedFilter ?? '') === 'todas' ? 'selected' : '' }}>Todas as concluídas</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-bold text-white transition hover:bg-gray-800">
                        Filtrar
                    </button>
                    <a href="{{ route('lab.reservations.index') }}" class="rounded-xl bg-gray-200 px-4 py-2 text-center text-xs font-bold text-gray-700 transition hover:bg-gray-300">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Kanban Board Container -->
        <div class="kanban-board-container" id="kanbanScrollContainer">
            <div class="kanban-board">
                @foreach($columns as $status => $label)
                    @php
                        $statusColor = $statusColors[$status] ?? '#94a3b8';
                        $resCount = $board[$status]->count();
                    @endphp

                    <div
                        class="kanban-column"
                        data-status="{{ $status }}"
                        style="border-top: 5px solid {{ $statusColor }};"
                    >
                        <!-- Column Header -->
                        <div class="mb-3 flex items-center justify-between gap-2 px-1">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="inline-block h-3 w-3 rounded-full flex-shrink-0" style="background-color: {{ $statusColor }};"></span>
                                <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-800 truncate">
                                    {{ $columnLabels[$status] ?? $label }}
                                </h2>
                                @if($status === 'concluida')
                                    @if(($selectedCompletedFilter ?? 'ativas') === 'ocultas')
                                        <span class="rounded-md bg-red-100 px-1.5 py-0.5 text-[9.5px] font-bold text-red-700">Ocultas</span>
                                    @elseif(($selectedCompletedFilter ?? 'ativas') === 'todas')
                                        <span class="rounded-md bg-blue-100 px-1.5 py-0.5 text-[9.5px] font-bold text-blue-700">Todas</span>
                                    @endif
                                @endif
                            </div>
                            <span
                                class="column-counter flex h-6 min-w-6 items-center justify-center rounded-full bg-white px-2 text-xs font-semibold text-gray-800 shadow-sm border border-gray-200"
                                data-column-counter="{{ $status }}"
                            >
                                {{ $resCount }}
                            </span>
                        </div>

                        <!-- Reservation List -->
                        <div class="task-list space-y-3">
                            @if($board[$status]->isEmpty())
                                <div class="empty-state rounded-2xl border border-dashed border-gray-400 bg-white/40 px-3 py-10 text-center text-xs font-semibold text-gray-600">
                                    @if($status === 'concluida' && ($selectedCompletedFilter ?? 'ativas') === 'ativas')
                                        Nenhuma recente (&le; 7 dias)
                                    @elseif($status === 'concluida' && ($selectedCompletedFilter ?? 'ativas') === 'ocultas')
                                        Nenhuma oculta (> 7 dias)
                                    @else
                                        Nenhuma reserva nesta etapa
                                    @endif
                                </div>
                            @else
                                @foreach($board[$status] as $res)
                                    @php
                                        $teacherId = $res->user?->id ?? 0;
                                        $teacherColor = $teacherColors[$teacherId % count($teacherColors)];
                                        $teacherName = $res->user?->name ?? 'Docente';
                                        $teacherInitials = collect(explode(' ', trim($teacherName)))
                                            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                            ->take(2)
                                            ->implode('');

                                        $resDate = $res->reservation_date ? \Carbon\Carbon::parse($res->reservation_date) : null;
                                        $isToday = $resDate && $resDate->isToday();
                                        $isPast = $resDate && $resDate->isPast() && !$isToday;
                                        $materialsData = base64_encode(json_encode($res->materials));
                                        $canCoordinate = auth()->user()->is_admin || auth()->user()->hasRole('Coordenador');
                                        $isAuxiliar = auth()->user()->hasRole('Auxiliar') || $canCoordinate;
                                    @endphp

                                    <div
                                        class="reservation-card flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-3.5 shadow-sm"
                                        data-search-text="{{ strtolower($res->id . ' ' . $teacherName . ' ' . ($res->space?->name ?? '') . ' ' . ($res->description ?? '') . ' ' . $res->materials->pluck('name')->implode(' ')) }}"
                                        style="border-left: 4px solid {{ $statusColor }};"
                                    >
                                        <!-- Header: Avatar, #ID, Space & Time -->
                                        <div>
                                            <div class="mb-2 flex items-start justify-between gap-2">
                                                <div class="flex items-center gap-2 min-w-0">
                                                    <span
                                                        class="inline-flex h-6 w-6 items-center justify-center rounded-full text-[10px] font-bold text-white shadow-sm flex-shrink-0"
                                                        style="background: {{ $teacherColor }};"
                                                        title="Docente: {{ $teacherName }}"
                                                    >
                                                        {{ $teacherInitials }}
                                                    </span>
                                                    <span class="text-xs font-bold text-gray-500">#{{ $res->id }}</span>
                                                </div>

                                                <span class="rounded-lg px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide bg-blue-50 text-blue-700 border border-blue-100 flex-shrink-0 truncate max-w-[140px]">
                                                    {{ $res->space?->name ?? 'Ambiente' }}
                                                </span>
                                            </div>

                                            <!-- Space Name & Date/Time info -->
                                            <div class="mb-2.5">
                                                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-800">
                                                    <svg class="h-4 w-4 text-gray-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    <span>{{ $resDate ? $resDate->format('d/m/Y') : 'Data não definida' }}</span>
                                                    @if($res->start_time)
                                                        <span class="text-gray-400 font-normal">às {{ substr($res->start_time, 0, 5) }}</span>
                                                    @endif
                                                    @if($isToday)
                                                        <span class="rounded-md bg-amber-100 px-1.5 py-0.5 text-[9px] font-semibold text-amber-800">Hoje</span>
                                                    @endif
                                                </div>

                                                @if($res->description)
                                                    <p class="mt-1.5 text-xs text-gray-600 line-clamp-2 leading-relaxed">
                                                        {{ $res->description }}
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Materials Pill Tags -->
                                            @if($res->materials && $res->materials->count() > 0)
                                                <div class="mb-2.5 flex flex-wrap items-center gap-1.5">
                                                    <button
                                                        type="button"
                                                        onclick="verChecklistPrevia('{{ addslashes($res->space?->name ?? 'Laboratório') }}', '{{ $materialsData }}')"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-2 py-1 text-[10px] font-bold text-amber-800 border border-amber-200 hover:bg-amber-100 transition"
                                                        title="Clique para ver lista completa de materiais"
                                                    >
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                        <span>{{ $res->materials->count() }} material(is)</span>
                                                    </button>

                                                    @foreach($res->materials->take(2) as $mat)
                                                        <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-0.5 text-[9.5px] font-medium text-gray-700 border border-gray-200 truncate max-w-[120px]">
                                                            {{ $mat->name }} (x{{ $mat->pivot->quantity_used ?? 1 }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>

                                        <!-- Card Footer: Teacher Info & Contextual Actions -->
                                        <div class="border-t border-gray-100 pt-2.5 mt-1">
                                            <div class="flex items-center justify-between gap-1.5 text-xs text-gray-600 mb-2.5">
                                                <div class="flex items-center gap-1.5 min-w-0 max-w-[140px]" title="Docente: {{ $teacherName }}">
                                                    <span class="inline-block h-2.5 w-2.5 rounded-full flex-shrink-0" style="background-color: {{ $teacherColor }};"></span>
                                                    <span class="truncate font-bold text-gray-800 text-xs">{{ $teacherName }}</span>
                                                </div>

                                                @if($res->auxiliar)
                                                    <span class="text-[9.5px] font-bold text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded-md border border-purple-100 truncate max-w-[100px]" title="Auxiliar: {{ $res->auxiliar->name }}">
                                                        AD: {{ $res->auxiliar->name }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Contextual Action Buttons -->
                                            <div class="flex flex-wrap items-center justify-between gap-1.5">
                                                <div class="flex items-center gap-1.5">
                                                    <a href="{{ route('lab.reservations.pdf', $res->id) }}" target="_blank" class="inline-flex items-center rounded-lg border border-gray-200 bg-gray-50 px-2 py-1 text-xs font-bold text-gray-700 hover:bg-gray-100 transition shadow-2xs" title="Imprimir Checklist PDF">
                                                        📄 PDF
                                                    </a>

                                                    <a href="{{ route('lab.reservations.show', $res->id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline px-1">
                                                        Ver
                                                    </a>
                                                </div>

                                                <div class="flex items-center gap-1.5">
                                                    {{-- Status 1: Solicitada (pre_alocada) -> Aprovar --}}
                                                    @if($res->status === 'pre_alocada' && $canCoordinate)
                                                        <form action="{{ route('lab.reservations.approve', $res->id) }}" method="POST" class="inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-[10.5px] font-bold text-white shadow-2xs transition hover:bg-emerald-700 active:scale-95">
                                                                APROVAR
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Status 2: Aprovada -> Iniciar Aula --}}
                                                    @if($res->status === 'aprovada')
                                                        <form action="{{ route('lab.reservations.start', $res->id) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="rounded-lg bg-emerald-600 px-2.5 py-1 text-[10.5px] font-bold text-white shadow-2xs transition hover:bg-emerald-700 active:scale-95">
                                                                INICIAR AULA
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Status 3: Em Aula (em_execucao) -> Encerrar & Relatar --}}
                                                    @if($res->status === 'em_execucao')
                                                        <button
                                                            type="button"
                                                            onclick="abrirModalProfessor({{ $res->id }})"
                                                            class="rounded-lg bg-blue-600 px-2.5 py-1 text-[10.5px] font-bold text-white shadow-2xs transition hover:bg-blue-700 active:scale-95"
                                                        >
                                                            RELATAR
                                                        </button>
                                                    @endif

                                                    {{-- Status 4: Conferência Técnica (aguardando_conferencia) -> Conferir Auxiliar --}}
                                                    @if($res->status === 'aguardando_conferencia' && $isAuxiliar)
                                                        <button
                                                            type="button"
                                                            onclick="abrirModalAuxiliar({{ $res->id }}, '{{ addslashes($res->professor_obs ?? '') }}')"
                                                            class="rounded-lg bg-amber-500 px-2.5 py-1 text-[10.5px] font-bold text-white shadow-2xs transition hover:bg-amber-600 active:scale-95"
                                                        >
                                                            CONFERIR
                                                        </button>
                                                    @endif

                                                    {{-- Status 5: Validação (aguardando_validacao) -> Validar Coordenador --}}
                                                    @if($res->status === 'aguardando_validacao' && $canCoordinate)
                                                        <form action="{{ route('lab.reservations.validate', $res->id) }}" method="POST" class="inline">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="rounded-lg bg-cyan-600 px-2.5 py-1 text-[10.5px] font-bold text-white shadow-2xs transition hover:bg-cyan-700 active:scale-95">
                                                                VALIDAR
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Status 6: Concluída / Finalizada --}}
                                                    @if(in_array($res->status, ['concluida', 'validada', 'finalizada']) && $res->scanned_doc_path)
                                                        <a href="{{ Storage::url($res->scanned_doc_path) }}" target="_blank" class="rounded-lg bg-teal-600 px-2 py-1 text-[10px] font-bold text-white shadow-2xs transition hover:bg-teal-700" title="Ver Documento Assinado">
                                                            Anexo
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- MODAL PRÉVIA DE MATERIAIS --}}
<div id="modalPrevia" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4 transition-all">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl border border-gray-100 transform transition-all scale-100">
        <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
            <h3 class="font-semibold text-base text-gray-800 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                </span>
                <span>Itens Solicitados: <span id="previaLocalTitle" class="text-blue-600 font-bold"></span></span>
            </h3>
            <button onclick="fecharModais()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>
        <div id="listaMateriaisContainer" class="space-y-2.5 mb-6 max-h-72 overflow-y-auto pr-1"></div>
        <button onclick="fecharModais()" class="w-full rounded-xl bg-gray-100 hover:bg-gray-200 py-3 text-xs font-bold text-gray-700 transition">
            Fechar
        </button>
    </div>
</div>

{{-- MODAL DO PROFESSOR (RELATAR ENCERRAMENTO) --}}
<div id="modalProfessor" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-blue-100 text-blue-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </span>
                <span>Encerrar Aula - Relato do Docente</span>
            </h3>
            <button onclick="fecharModais()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>
        <form id="formProfessor" method="POST">
            @csrf
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Observações da Aula / Ocorrências</label>
            <textarea name="professor_obs" class="w-full border border-gray-300 rounded-xl p-3.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:ring-2 focus:ring-blue-400 focus:outline-none mb-4" rows="4" placeholder="Descreva como foi a aula, se houve algum imprevisto pedagógico ou sobre o ambiente..." required></textarea>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="fecharModais()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-sm transition">Enviar Relato</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DO AUXILIAR (CONFERÊNCIA TÉCNICA) --}}
<div id="modalAuxiliar" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4 transition-all">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-3">
            <h3 class="text-base font-semibold text-gray-800 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-amber-100 text-amber-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                <span>Conferência Técnica - Auxiliar</span>
            </h3>
            <button onclick="fecharModais()" class="text-gray-400 hover:text-gray-600 text-2xl font-bold">&times;</button>
        </div>

        <div class="mb-3.5 rounded-xl bg-gray-50 p-3 border border-gray-200">
            <span class="text-[10.5px] font-bold text-gray-400 uppercase block">Relato do Professor:</span>
            <p id="obsProfessorTexto" class="text-xs sm:text-sm text-gray-700 italic mt-1"></p>
        </div>

        <form id="formAuxiliar" method="POST">
            @csrf
            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Laudo / Conferência Técnica</label>
            <textarea name="auxiliar_obs" class="w-full border border-gray-300 rounded-xl p-3.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:ring-2 focus:ring-amber-400 focus:outline-none mb-4" rows="4" placeholder="Relate o estado dos equipamentos, organização do laboratório e devolução dos materiais..." required></textarea>
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="fecharModais()" class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-sm transition">Finalizar Conferência</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('reservationSearchInput');
        const matchCountEl = document.getElementById('searchMatchCount');
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterFormContent = document.getElementById('filterFormContent');
        const filterChevron = document.getElementById('filterChevron');
        const mobileTabs = document.querySelectorAll('.mobile-col-tab');

        // Mobile Filter Accordion Toggle
        if (toggleFilterBtn && filterFormContent) {
            toggleFilterBtn.addEventListener('click', function () {
                if (window.innerWidth < 640) {
                    const isHidden = filterFormContent.classList.contains('hidden');
                    if (isHidden) {
                        filterFormContent.classList.remove('hidden');
                        if (filterChevron) filterChevron.classList.add('rotate-180');
                    } else {
                        filterFormContent.classList.add('hidden');
                        if (filterChevron) filterChevron.classList.remove('rotate-180');
                    }
                }
            });
        }

        // Mobile Column Navigation Tabs
        mobileTabs.forEach((tab) => {
            tab.addEventListener('click', function () {
                const targetStatus = tab.dataset.targetStatus;
                const targetCol = document.querySelector(`.kanban-column[data-status="${targetStatus}"]`);
                if (targetCol) {
                    targetCol.scrollIntoView({ behavior: 'smooth', inline: 'start', block: 'nearest' });
                    mobileTabs.forEach(t => t.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50'));
                    tab.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
                }
            });
        });

        // Live Client-side Search Filter
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = searchInput.value.trim().toLowerCase();
                const cards = document.querySelectorAll('.reservation-card');
                let matchCount = 0;

                cards.forEach((card) => {
                    const text = card.dataset.searchText || '';
                    if (!query || text.includes(query)) {
                        card.style.display = '';
                        matchCount++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                if (query) {
                    matchCountEl.textContent = `${matchCount} encontrada(s)`;
                    matchCountEl.classList.remove('hidden');
                } else {
                    matchCountEl.classList.add('hidden');
                }
            });
        }
    });

    function verChecklistPrevia(local, materiaisBase64) {
        const materiais = JSON.parse(atob(materiaisBase64));
        const container = document.getElementById('listaMateriaisContainer');
        document.getElementById('previaLocalTitle').innerText = local;
        container.innerHTML = "";
        if (materiais.length > 0) {
            materiais.forEach(m => {
                const qtd = m.pivot ? (m.pivot.quantity_used || m.pivot.quantity || 1) : '1';
                container.innerHTML += `
                    <div class="flex items-center justify-between bg-gray-50 px-3.5 py-2.5 rounded-xl border border-gray-200 text-xs sm:text-sm">
                        <span class="font-medium text-gray-800">${m.name}</span>
                        <span class="font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">x${qtd}</span>
                    </div>`;
            });
        } else {
            container.innerHTML = '<p class="text-xs italic text-gray-500 text-center py-4">Nenhum material extra solicitado.</p>';
        }
        document.getElementById('modalPrevia').classList.remove('hidden');
    }

    function abrirModalProfessor(id) {
        const form = document.getElementById('formProfessor');
        form.action = `/laboratorio/reservas/${id}/obs-professor`;
        document.getElementById('modalProfessor').classList.remove('hidden');
    }

    function abrirModalAuxiliar(id, obsProfessor) {
        const form = document.getElementById('formAuxiliar');
        form.action = `/laboratorio/reservas/${id}/conferencia`;
        document.getElementById('obsProfessorTexto').innerText = obsProfessor ? `"${obsProfessor}"` : "Nenhuma observação registrada.";
        document.getElementById('modalAuxiliar').classList.remove('hidden');
    }

    function fecharModais() {
        document.querySelectorAll('[id$="modal"], [id^="modal"]').forEach(m => m.classList.add('hidden'));
    }
</script>
@endsection
