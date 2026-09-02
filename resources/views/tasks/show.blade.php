@extends('layouts.operational')

@section('content')
@php
    $statusColors = [
        'atribuida' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-300', 'label' => 'Atribuída'],
        'em_andamento' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-300', 'label' => 'Em Andamento'],
        'em_execucao' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-300', 'label' => 'Em execução'],
        'devolvida' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-300', 'label' => 'Devolvida'],
        'concluida' => ['bg' => 'bg-sky-100', 'text' => 'text-sky-800', 'border' => 'border-sky-300', 'label' => 'Concluída'],
    ];

    $currStatus = $statusColors[$task->status] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-300', 'label' => ucfirst($task->status)];
    $isOverdue = $task->due_date && $task->due_date->isPast() && !$task->due_date->isToday() && $task->status !== 'concluida';
@endphp

<div class="min-h-screen bg-gray-50 px-4 py-8">
    <div class="mx-auto max-w-5xl">
        <!-- Top Bar / Navigation -->
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Voltar ao Kanban
                </a>
                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Atividade #{{ $task->id }}</span>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide border {{ $currStatus['bg'] }} {{ $currStatus['text'] }} {{ $currStatus['border'] }}">
                    {{ $currStatus['label'] }}
                </span>
                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wide
                    @if($task->priority === 'alta') bg-red-100 text-red-700 border border-red-200
                    @elseif($task->priority === 'media') bg-yellow-100 text-yellow-800 border border-yellow-200
                    @else bg-green-100 text-green-700 border border-green-200
                    @endif">
                    Prioridade {{ $task->priority === 'alta' ? 'Alta' : ($task->priority === 'media' ? 'Média' : 'Baixa') }}
                </span>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm font-semibold text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content: Details & Description -->
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 leading-tight mb-4">
                        {{ $task->title }}
                    </h1>

                    <div class="mb-6">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Descrição da Atividade</h2>
                        <div class="rounded-xl bg-gray-50 p-4 text-sm leading-relaxed text-gray-700 whitespace-pre-line border border-gray-100">
                            {{ $task->description }}
                        </div>
                    </div>

                    <!-- Meta details grid -->
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 border-t border-gray-100 pt-5 text-sm">
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Departamento</span>
                            <span class="font-semibold text-gray-800">{{ $task->department?->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Curso</span>
                            <span class="font-semibold text-gray-800">{{ $task->course?->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Responsável (Supervisor)</span>
                            <span class="font-semibold text-gray-800">{{ $task->responsible?->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Executor (Atribuído)</span>
                            <span class="font-semibold text-gray-800">{{ $task->assignee?->name ?? '—' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Prazo de Entrega</span>
                            <span class="font-semibold {{ $isOverdue ? 'text-red-600 font-bold' : 'text-gray-800' }}">
                                {{ $task->due_date ? $task->due_date->format('d/m/Y') : 'Não estipulado' }}
                                @if($isOverdue)
                                    <span class="ml-1 text-xs text-red-500">(Atrasado)</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="block text-xs font-bold text-gray-400 uppercase">Criado por / em</span>
                            <span class="font-semibold text-gray-800">{{ $task->creator?->name ?? 'Sistema' }} em {{ $task->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status Update Form -->
                <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-sm font-bold uppercase tracking-wider text-gray-800 mb-4 flex items-center gap-2">
                        <svg class="h-4 w-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                        Atualizar Status da Atividade
                    </h2>

                    <form method="POST" action="{{ route('tasks.status', $task) }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Novo Status</label>
                                <select name="status" class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                                    <option value="atribuida" {{ $task->status === 'atribuida' ? 'selected' : '' }}>Atribuída</option>
                                    <option value="em_andamento" {{ $task->status === 'em_andamento' ? 'selected' : '' }}>Em Andamento</option>
                                    <option value="em_execucao" {{ $task->status === 'em_execucao' ? 'selected' : '' }}>Em execução</option>
                                    <option value="devolvida" {{ $task->status === 'devolvida' ? 'selected' : '' }}>Devolvida</option>
                                    <option value="concluida" {{ $task->status === 'concluida' ? 'selected' : '' }}>Concluída</option>
                                </select>
                            </div>

                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Observação / Justificativa</label>
                                <input type="text" name="comment" placeholder="Descreva brevemente a mudança (opcional)..." class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-300" />
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm transition hover:bg-indigo-500">
                                Salvar Mudança de Status
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar: History Timeline & Comments -->
            <div class="space-y-6">
                <!-- Timeline of Status Changes -->
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-4 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Histórico de Mudanças
                    </h2>

                    @if($task->history && $task->history->isNotEmpty())
                        <div class="relative pl-6 space-y-4 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200">
                            @foreach($task->history as $h)
                                <div class="relative text-xs">
                                    <div class="absolute -left-6 top-1 h-3 w-3 rounded-full border-2 border-white bg-indigo-600"></div>
                                    <div class="font-bold text-gray-800">
                                        {{ $statusColors[$h->to_status]['label'] ?? ucfirst($h->to_status) }}
                                    </div>
                                    <div class="text-[11px] text-gray-500 mt-0.5">
                                        por <span class="font-semibold text-gray-700">{{ $h->user?->name ?? 'Sistema' }}</span> em {{ $h->created_at->format('d/m/Y H:i') }}
                                    </div>
                                    @if($h->comment)
                                        <p class="mt-1 rounded bg-gray-50 p-2 text-[11px] text-gray-600 italic border border-gray-100">
                                            "{{ $h->comment }}"
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 italic">Nenhum histórico registrado.</p>
                    @endif
                </div>

                <!-- Comments & Attachments -->
                @if($task->comments && $task->comments->isNotEmpty())
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-800 mb-3">Comentários</h2>
                        <div class="space-y-3">
                            @foreach($task->comments as $comment)
                                <div class="rounded-xl bg-gray-50 p-3 text-xs border border-gray-100">
                                    <div class="flex justify-between font-semibold text-gray-700 mb-1">
                                        <span>{{ $comment->user?->name ?? 'Usuário' }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $comment->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <p class="text-gray-600">{{ $comment->comment }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

