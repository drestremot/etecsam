@extends('layouts.operational')

@section('content')
@php
    $statusColors = [
        'atribuida' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'label' => 'Atribuída'],
        'em_andamento' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'label' => 'Em Andamento'],
        'em_execucao' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'label' => 'Em execução'],
        'devolvida' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Devolvida'],
        'concluida' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-800', 'label' => 'Concluída'],
    ];
@endphp

<div class="min-h-screen bg-gray-50 px-4 py-8">
    <div class="mx-auto max-w-6xl">
        <!-- Header -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <span>Relatório de Atividades</span>
                    <span class="text-xs font-bold text-gray-500 bg-gray-200 rounded-lg px-2.5 py-1">{{ $tasks->count() }} registro(s)</span>
                </h1>
                <p class="text-xs text-gray-500 mt-1">Filtre e analise todas as demandas registradas no sistema</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Voltar ao Kanban
                </a>
            </div>
        </div>

        <!-- Filters Form -->
        <div class="mb-6 bg-white p-5 rounded-2xl border border-gray-200 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Departamento</label>
                    <select name="department_id" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Todos os departamentos</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Usuário</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Todos os usuários</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Data Início</label>
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Data Fim</label>
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-gray-800">
                        Filtrar
                    </button>
                    <a href="{{ route('tasks.report') }}" class="rounded-xl bg-gray-200 px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:bg-gray-300">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">#ID & Título</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Prioridade</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Responsável</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Departamento</th>
                            <th class="px-5 py-3.5 text-left text-[11px] font-bold text-gray-500 uppercase tracking-wider">Criação</th>
                            <th class="px-5 py-3.5 text-right text-[11px] font-bold text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white text-xs">
                        @forelse($tasks as $task)
                            @php
                                $statusInfo = $statusColors[$task->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => ucfirst($task->status)];
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-gray-900">#{{ $task->id }} - {{ $task->title }}</div>
                                    @if($task->course)
                                        <div class="text-[10px] text-indigo-600 font-medium mt-0.5">{{ $task->course->name }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wide {{ $statusInfo['bg'] }} {{ $statusInfo['text'] }}">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold
                                        @if($task->priority === 'alta') bg-red-100 text-red-700
                                        @elseif($task->priority === 'media') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-700
                                        @endif">
                                        {{ ucfirst($task->priority ?? 'Média') }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-gray-700 font-medium">
                                    {{ $task->responsible?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-600">
                                    {{ $task->department?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500">
                                    {{ $task->created_at->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <a href="{{ route('tasks.show', $task) }}" class="font-bold text-indigo-600 hover:text-indigo-800 hover:underline">
                                        Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-gray-500">
                                    Nenhuma atividade encontrada para os filtros selecionados.
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
