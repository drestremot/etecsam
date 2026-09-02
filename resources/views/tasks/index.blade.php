@extends('layouts.operational')

@section('content')
@php
    $collaboratorColors = [
        '#2563eb', '#7c3aed', '#ea580c', '#16a34a', '#dc2626', '#0891b2', '#e11d48', '#65a30d', '#f59e0b', '#0f766e'
    ];

    $columnLabels = [
        'atribuida' => 'Atribuída',
        'em_andamento' => 'Em Andamento',
        'em_execucao' => 'Em execução',
        'devolvida' => 'Devolvida',
        'concluida' => 'Concluída',
    ];

    $statusColors = [
        'atribuida' => '#2f80ed',
        'em_andamento' => '#f2994a',
        'em_execucao' => '#27ae60',
        'devolvida' => '#eb5757',
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

    @media (min-width: 1800px) {
        .kanban-board {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            width: 100%;
        }
        .kanban-column {
            max-width: none;
            min-width: 0;
        }
    }

    .kanban-column.drag-over {
        background: #d2d7e0;
        box-shadow: 0 0 0 2px #6366f1, inset 0 0 0 1px rgba(99, 102, 241, 0.2);
    }

    .kanban-column .task-list {
        min-height: 160px;
    }

    .task-card {
        box-shadow: 0 1px 4px rgba(15, 23, 42, 0.08);
        transition: transform 0.15s ease, box-shadow 0.15s ease, opacity 0.15s ease;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px -2px rgba(15, 23, 42, 0.12), 0 3px 6px -2px rgba(15, 23, 42, 0.08);
    }

    .task-card.is-dragging {
        opacity: 0.45;
        transform: scale(0.98);
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
    <div class="w-full max-w-[1850px] mx-auto">
        <!-- Toast Container -->
        <div id="toastContainer" class="fixed top-5 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

        <!-- Floating Action Button (Mobile Only) -->
        <a href="{{ route('tasks.create') }}" class="lg:hidden fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-indigo-600 text-white shadow-xl transition active:scale-95 hover:bg-indigo-500 focus:outline-none" title="Nova Atividade">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Top Header -->
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-[0.15em] text-gray-800 uppercase flex items-center gap-2">
                    <span>KanbanTec</span>
                    <span class="rounded-lg bg-indigo-100 px-2.5 py-0.5 text-xs font-bold text-indigo-700 normal-case tracking-normal">Quadro</span>
                </h1>
                <p class="text-[11px] text-gray-600 font-medium mt-0.5">Acompanhamento e gestão operacional</p>
            </div>

            <div class="hidden sm:flex flex-wrap items-center gap-2.5">
                <a href="{{ route('tasks.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-indigo-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Nova atividade</span>
                </a>
                <a href="{{ route('tasks.report') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-800 px-3.5 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-gray-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Relatório</span>
                </a>
            </div>
        </div>

        <!-- Mobile Column Navigation Tabs -->
        <div class="lg:hidden mb-3 flex items-center gap-1.5 overflow-x-auto pb-1.5 -mx-1 px-1 no-scrollbar">
            @foreach($columns as $status => $label)
                @php
                    $tabColor = $statusColors[$status] ?? '#94a3b8';
                @endphp
                <button
                    type="button"
                    class="mobile-col-tab flex-shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white/90 px-3 py-1.5 text-xs font-bold text-gray-700 shadow-sm active:bg-gray-100"
                    data-target-status="{{ $status }}"
                >
                    <span class="inline-block h-2 w-2 rounded-full" style="background-color: {{ $tabColor }};"></span>
                    <span>{{ $columnLabels[$status] ?? $label }}</span>
                    <span class="rounded-full bg-gray-100 px-1.5 text-[10px] font-bold text-gray-600">{{ $board[$status]->count() }}</span>
                </button>
            @endforeach
        </div>

        <!-- Filter & Search Bar with Mobile Accordion Toggle -->
        <div class="mb-4 rounded-2xl border border-gray-300 bg-white/60 p-3 shadow-sm backdrop-blur-sm">
            <div class="flex items-center justify-between gap-2 cursor-pointer sm:cursor-default" id="toggleFilterBtn">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                    <span class="text-xs font-bold text-gray-800 uppercase tracking-wide">Filtros & Busca</span>
                    @if(request('department_id') || request('user_id') || request('priority') || (request('completed_filter') && request('completed_filter') !== 'ativas'))
                        <span class="rounded-full bg-indigo-600 px-1.5 py-0.2 text-[10px] font-bold text-white">Ativos</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('tasks.report') }}" class="sm:hidden text-xs font-bold text-gray-700 underline mr-1">Relatório</a>
                    <button type="button" class="sm:hidden text-gray-500 hover:text-gray-800">
                        <svg id="filterChevron" class="h-4 w-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
            </div>

            <!-- Real-time Live Search Input (Always Visible) -->
            <div class="mt-2.5 flex items-center gap-2">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </span>
                    <input
                        type="text"
                        id="kanbanSearchInput"
                        placeholder="Busca em tempo real (título, descrição, #ID ou colaborador)..."
                        class="w-full pl-8 pr-3 py-1.5 rounded-xl border border-gray-300 bg-white text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300"
                    />
                </div>
                <span id="searchMatchCount" class="text-[11px] text-gray-500 font-medium hidden"></span>
            </div>

            <!-- Expandable Server Filters -->
            <form id="filterFormContent" method="GET" class="hidden sm:grid grid-cols-1 gap-2.5 sm:grid-cols-2 md:grid-cols-6 mt-3 pt-3 border-t border-gray-200/70">
                <div>
                    <label class="block text-[10.5px] font-bold text-gray-600 uppercase mb-1">Departamento</label>
                    <select name="department_id" class="w-full rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Todos os departamentos</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10.5px] font-bold text-gray-600 uppercase mb-1">Colaborador</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10.5px] font-bold text-gray-600 uppercase mb-1">Prioridade</label>
                    <select name="priority" class="w-full rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Todas as prioridades</option>
                        <option value="alta" {{ request('priority') === 'alta' ? 'selected' : '' }}>Alta</option>
                        <option value="media" {{ request('priority') === 'media' ? 'selected' : '' }}>Média</option>
                        <option value="baixa" {{ request('priority') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10.5px] font-bold text-gray-600 uppercase mb-1">Concluídas</label>
                    <select name="completed_filter" class="w-full rounded-xl border border-gray-300 bg-white px-2.5 py-1.5 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="ativas" {{ ($selectedCompletedFilter ?? 'ativas') === 'ativas' ? 'selected' : '' }}>Ativas (≤ 7 dias)</option>
                        <option value="ocultas" {{ ($selectedCompletedFilter ?? '') === 'ocultas' ? 'selected' : '' }}>Ocultas (> 7 dias)</option>
                        <option value="todas" {{ ($selectedCompletedFilter ?? '') === 'todas' ? 'selected' : '' }}>Todas as concluídas</option>
                    </select>
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-gray-800">
                        Filtrar
                    </button>
                    <a href="{{ route('tasks.index') }}" class="rounded-xl bg-gray-200 px-3 py-1.5 text-center text-xs font-semibold text-gray-700 transition hover:bg-gray-300">
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
                        $taskCount = $board[$status]->count();
                    @endphp

                    <div
                        class="kanban-column"
                        data-status="{{ $status }}"
                        style="border-top: 4px solid {{ $statusColor }};"
                    >
                        <!-- Column Header -->
                        <div class="mb-2.5 flex items-center justify-between gap-1.5 px-1">
                            <div class="flex items-center gap-1.5 min-w-0">
                                <span class="inline-block h-2 w-2 rounded-full flex-shrink-0" style="background-color: {{ $statusColor }};"></span>
                                <h2 class="text-[11px] font-bold uppercase tracking-wider text-gray-700 truncate">
                                    {{ $columnLabels[$status] ?? $label }}
                                </h2>
                                @if($status === 'concluida')
                                    @if(($selectedCompletedFilter ?? 'ativas') === 'ocultas')
                                        <span class="rounded bg-red-100 px-1 py-0.2 text-[8.5px] font-bold text-red-700">Ocultas</span>
                                    @elseif(($selectedCompletedFilter ?? 'ativas') === 'todas')
                                        <span class="rounded bg-indigo-100 px-1 py-0.2 text-[8.5px] font-bold text-indigo-700">Todas</span>
                                    @endif
                                @endif
                            </div>
                            <span
                                class="column-counter flex h-5 min-w-5 items-center justify-center rounded-full bg-white px-1.5 text-[10px] font-bold text-gray-700 shadow-sm border border-gray-200"
                                data-column-counter="{{ $status }}"
                            >
                                {{ $taskCount }}
                            </span>
                        </div>

                        <!-- Task List / Drop Zone -->
                        <div class="task-list space-y-2">
                            @if($board[$status]->isEmpty())
                                <div class="empty-state mt-3 rounded-xl border border-dashed border-gray-400 bg-white/25 px-2 py-8 text-center text-xs font-medium text-gray-600">
                                    @if($status === 'concluida' && ($selectedCompletedFilter ?? 'ativas') === 'ativas')
                                        Nenhuma recente (&le; 7 dias)
                                    @elseif($status === 'concluida' && ($selectedCompletedFilter ?? 'ativas') === 'ocultas')
                                        Nenhuma oculta (> 7 dias)
                                    @else
                                        Nenhuma atividade
                                    @endif
                                </div>
                            @else
                                @foreach($board[$status] as $task)
                                    @php
                                        $ownerId = $task->assignee?->id ?? $task->responsible?->id ?? $task->creator?->id ?? 0;
                                        $ownerColor = $collaboratorColors[$ownerId % count($collaboratorColors)];
                                        $ownerName = $task->assignee?->name ?? $task->responsible?->name ?? $task->creator?->name ?? 'Sem colaborador';
                                        $ownerInitials = collect(explode(' ', trim($ownerName)))
                                            ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                                            ->take(2)
                                            ->implode('');

                                        $isOverdue = $task->due_date && $task->due_date->isPast() && !$task->due_date->isToday() && $task->status !== 'concluida';
                                        $isToday = $task->due_date && $task->due_date->isToday() && $task->status !== 'concluida';
                                    @endphp

                                    <div
                                        class="task-card flex flex-col justify-between rounded-xl border border-gray-200 bg-white p-2.5 shadow-sm active:cursor-grabbing cursor-grab"
                                        draggable="true"
                                        data-task-id="{{ $task->id }}"
                                        data-status="{{ $task->status }}"
                                        data-search-text="{{ strtolower($task->id . ' ' . $task->title . ' ' . $task->description . ' ' . $ownerName . ' ' . ($task->department?->name ?? '') . ' ' . ($task->course?->name ?? '')) }}"
                                        style="border-top: 4px solid {{ $statusColor }};"
                                    >
                                        <!-- Card Header: ID, Avatar, Priority Badge -->
                                        <div class="mb-1.5 flex items-start justify-between gap-1.5">
                                            <div class="flex items-center gap-1.5 min-w-0">
                                                <span
                                                    class="inline-flex h-4.5 w-4.5 items-center justify-center rounded-full text-[8.5px] font-bold text-white shadow-sm flex-shrink-0"
                                                    style="background: {{ $ownerColor }};"
                                                    title="{{ $ownerName }}"
                                                >
                                                    {{ $ownerInitials }}
                                                </span>
                                                <span class="text-[10px] font-bold text-gray-500">#{{ $task->id }}</span>
                                            </div>

                                            <span class="rounded-full px-1.5 py-0.5 text-[8.5px] font-bold uppercase tracking-wide flex-shrink-0
                                                @if($task->priority === 'alta') bg-red-100 text-red-700 border border-red-200
                                                @elseif($task->priority === 'media') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @else bg-green-100 text-green-700 border border-green-200
                                                @endif">
                                                {{ $task->priority === 'alta' ? 'Alta' : ($task->priority === 'media' ? 'Média' : 'Baixa') }}
                                            </span>
                                        </div>

                                        <!-- Title & Truncated Description -->
                                        <div class="mb-2">
                                            <h3 class="text-[11.5px] font-bold leading-snug text-gray-800 line-clamp-2">
                                                {{ $task->title }}
                                            </h3>
                                            @if($task->description)
                                                <p class="mt-1 text-[10.5px] text-gray-500 line-clamp-2 leading-tight">
                                                    {{ Str::limit($task->description, 70) }}
                                                </p>
                                            @endif
                                        </div>

                                        <!-- Tags: Department & Course & Due Date -->
                                        <div class="mb-2 flex flex-wrap items-center gap-1">
                                            @if($task->department)
                                                <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-[9px] font-medium text-gray-600 border border-gray-200 truncate max-w-full">
                                                    {{ $task->department->name }}
                                                </span>
                                            @endif

                                            @if($task->course)
                                                <span class="inline-flex items-center rounded bg-indigo-50 px-1.5 py-0.5 text-[9px] font-semibold text-indigo-700 border border-indigo-100 truncate max-w-full">
                                                    {{ $task->course->name }}
                                                </span>
                                            @endif

                                            @if($task->due_date)
                                                <span class="inline-flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[9px] font-semibold
                                                    @if($isOverdue) bg-red-100 text-red-700 border border-red-200
                                                    @elseif($isToday) bg-amber-100 text-amber-800 border border-amber-200
                                                    @else bg-slate-100 text-slate-600 border border-slate-200
                                                    @endif"
                                                >
                                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                                    {{ $isOverdue ? 'Atrasado: ' . $task->due_date->format('d/m') : ($isToday ? 'Hoje' : $task->due_date->format('d/m/Y')) }}
                                                </span>
                                            @endif

                                            @if($task->status === 'concluida')
                                                @php
                                                    $completedDate = $task->completed_at ?? $task->updated_at;
                                                    $isOlderThanWeek = $completedDate && $completedDate->lt(now()->subDays(7));
                                                @endphp
                                                <span class="inline-flex items-center gap-0.5 rounded px-1.5 py-0.5 text-[9px] font-semibold {{ $isOlderThanWeek ? 'bg-gray-100 text-gray-500 border border-gray-200' : 'bg-sky-50 text-sky-700 border border-sky-100' }}" title="Concluída em {{ $completedDate ? $completedDate->format('d/m/Y H:i') : '' }}">
                                                    <svg class="h-2.5 w-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    {{ $completedDate ? 'Concl: ' . $completedDate->format('d/m') : 'Concluída' }}
                                                    @if($isOlderThanWeek)
                                                        <span class="text-[8px] text-gray-400 font-normal">(oculta)</span>
                                                    @endif
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Card Footer: Collaborator info & Quick Actions -->
                                        <div class="flex items-center justify-between gap-1.5 border-t border-gray-100 pt-1.5 text-[10px] text-gray-600">
                                            <div class="flex items-center gap-1 min-w-0 max-w-[100px] sm:max-w-[115px]" title="Atribuído a {{ $ownerName }}">
                                                <span class="inline-block h-2 w-2 rounded-full flex-shrink-0" style="background-color: {{ $ownerColor }};"></span>
                                                <span class="truncate font-medium text-gray-700">{{ $ownerName }}</span>
                                            </div>

                                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                                <select
                                                    class="quick-status-select rounded border border-gray-200 bg-gray-50 px-1 py-0.5 text-[9.5px] text-gray-600 focus:outline-none focus:ring-1 focus:ring-indigo-300"
                                                    data-task-id="{{ $task->id }}"
                                                    title="Mover para outro status"
                                                >
                                                    <option value="" disabled selected>Mover...</option>
                                                    @foreach($columns as $optStatus => $optLabel)
                                                        @if($optStatus !== $task->status)
                                                            <option value="{{ $optStatus }}">{{ $columnLabels[$optStatus] ?? $optLabel }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>

                                                <a href="{{ route('tasks.show', $task) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                                    Abrir
                                                </a>
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const columns = document.querySelectorAll('.kanban-column');
        const toastContainer = document.getElementById('toastContainer');
        const searchInput = document.getElementById('kanbanSearchInput');
        const matchCountEl = document.getElementById('searchMatchCount');
        const toggleFilterBtn = document.getElementById('toggleFilterBtn');
        const filterFormContent = document.getElementById('filterFormContent');
        const filterChevron = document.getElementById('filterChevron');
        const mobileTabs = document.querySelectorAll('.mobile-col-tab');

        const statusColors = {
            'atribuida': '#2f80ed',
            'em_andamento': '#f2994a',
            'em_execucao': '#27ae60',
            'devolvida': '#eb5757',
            'concluida': '#56ccf2'
        };

        const statusLabels = {
            'atribuida': 'Atribuída',
            'em_andamento': 'Em Andamento',
            'em_execucao': 'Em execução',
            'devolvida': 'Devolvida',
            'concluida': 'Concluída'
        };

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
                    mobileTabs.forEach(t => t.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50'));
                    tab.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');
                }
            });
        });

        // Toast Notification System
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            const isSuccess = type === 'success';
            toast.className = `pointer-events-auto flex items-center gap-2 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-white shadow-lg transition-all duration-300 transform translate-y-2 opacity-0 ${
                isSuccess ? 'bg-emerald-600' : 'bg-red-600'
            }`;

            const iconSvg = isSuccess
                ? '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                : '<svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>';

            toast.innerHTML = `${iconSvg}<span>${message}</span>`;
            toastContainer.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('translate-y-2', 'opacity-0');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => toast.remove(), 300);
            }, 3500);
        }

        // Placeholder management
        function ensurePlaceholder(taskList) {
            if (!taskList) return;
            if (taskList.querySelector('.empty-state') || taskList.querySelector('.task-card')) return;

            const placeholder = document.createElement('div');
            placeholder.className = 'empty-state mt-3 rounded-xl border border-dashed border-gray-400 bg-white/25 px-2 py-8 text-center text-xs font-medium text-gray-600';
            placeholder.textContent = 'Nenhuma atividade';
            taskList.appendChild(placeholder);
        }

        function removePlaceholder(taskList) {
            if (!taskList) return;
            const placeholder = taskList.querySelector('.empty-state');
            if (placeholder) {
                placeholder.remove();
            }
        }

        // Column counter & Mobile Tab counter dynamic update
        function updateColumnCounter(columnStatus, delta) {
            const counterEl = document.querySelector(`.column-counter[data-column-counter="${columnStatus}"]`);
            if (counterEl) {
                let currentVal = parseInt(counterEl.textContent.trim(), 10) || 0;
                let newVal = Math.max(0, currentVal + delta);
                counterEl.textContent = newVal;
            }

            const tabCounterEl = document.querySelector(`.tab-counter[data-tab-counter="${columnStatus}"]`);
            if (tabCounterEl) {
                let currentVal = parseInt(tabCounterEl.textContent.trim(), 10) || 0;
                let newVal = Math.max(0, currentVal + delta);
                tabCounterEl.textContent = newVal;
            }
        }

        // Move task helper function
        async function moveTaskToStatus(taskId, newStatus, cardElement) {
            if (!cardElement || !taskId || !newStatus) return;

            const previousStatus = cardElement.dataset.status;
            if (previousStatus === newStatus) return;

            const previousColumn = document.querySelector(`.kanban-column[data-status="${previousStatus}"]`);
            const targetColumn = document.querySelector(`.kanban-column[data-status="${newStatus}"]`);

            if (!previousColumn || !targetColumn) return;

            const previousTaskList = previousColumn.querySelector('.task-list');
            const targetTaskList = targetColumn.querySelector('.task-list');

            // Move DOM element optimistically
            removePlaceholder(targetTaskList);
            targetTaskList.appendChild(cardElement);
            cardElement.dataset.status = newStatus;
            cardElement.style.borderTopColor = statusColors[newStatus] || '#94a3b8';

            // Update column counters
            updateColumnCounter(previousStatus, -1);
            updateColumnCounter(newStatus, 1);

            // Re-check placeholders
            if (!previousTaskList.querySelector('.task-card')) {
                ensurePlaceholder(previousTaskList);
            }

            try {
                const response = await fetch(`/tasks/${taskId}/status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    body: new URLSearchParams({
                        _token: csrfToken,
                        status: newStatus
                    })
                });

                const data = await response.json().catch(() => null);

                if (!response.ok) {
                    const errorMsg = (data && data.message) ? data.message : 'Falha ao atualizar o status da atividade.';
                    throw new Error(errorMsg);
                }

                showToast(`Atividade #${taskId} movida para ${statusLabels[newStatus] || newStatus}`, 'success');
            } catch (error) {
                // Rollback DOM and counters
                cardElement.dataset.status = previousStatus;
                cardElement.style.borderTopColor = statusColors[previousStatus] || '#94a3b8';
                previousTaskList.appendChild(cardElement);

                updateColumnCounter(newStatus, -1);
                updateColumnCounter(previousStatus, 1);

                removePlaceholder(previousTaskList);
                if (!targetTaskList.querySelector('.task-card')) {
                    ensurePlaceholder(targetTaskList);
                }

                showToast(error.message || 'Não foi possível mover a tarefa.', 'error');
            }
        }

        // Drag and Drop Listeners
        columns.forEach((column) => {
            column.addEventListener('dragover', function (event) {
                event.preventDefault();
                column.classList.add('drag-over');
            });

            column.addEventListener('dragleave', function () {
                column.classList.remove('drag-over');
            });

            column.addEventListener('drop', async function (event) {
                event.preventDefault();
                column.classList.remove('drag-over');

                const taskId = event.dataTransfer.getData('text/plain');
                const newStatus = column.dataset.status;
                const card = document.querySelector(`.task-card[data-task-id="${taskId}"]`);

                if (!taskId || !card || card.dataset.status === newStatus) {
                    return;
                }

                await moveTaskToStatus(taskId, newStatus, card);
            });
        });

        // Task Cards Drag Events
        document.querySelectorAll('.task-card').forEach((card) => {
            card.addEventListener('dragstart', function (event) {
                event.dataTransfer.setData('text/plain', card.dataset.taskId);
                event.dataTransfer.effectAllowed = 'move';
                card.classList.add('is-dragging');
            });

            card.addEventListener('dragend', function () {
                card.classList.remove('is-dragging');
            });
        });

        // Quick Move Select (Mobile / Accessibility)
        document.querySelectorAll('.quick-status-select').forEach((select) => {
            select.addEventListener('change', async function () {
                const newStatus = select.value;
                const taskId = select.dataset.taskId;
                const card = select.closest('.task-card');

                if (newStatus && taskId && card) {
                    await moveTaskToStatus(taskId, newStatus, card);
                    select.selectedIndex = 0; // Reset dropdown to placeholder
                }
            });
        });

        // Live Client-Side Search Filter
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = searchInput.value.trim().toLowerCase();
                const cards = document.querySelectorAll('.task-card');
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
</script>
@endsection
