@extends('admin.layouts.app')

@section('title', 'Reservas de Laboratório')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Reservas</h1>
        <a href="{{ route('lab.reservations.create') }}"
           class="inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2.5 rounded-lg hover:bg-etec-main transition font-semibold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Nova Reserva
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    {{-- ── Filtros de busca ── --}}
    <form method="GET" action="{{ route('lab.reservations.index') }}"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-4">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">

            {{-- Busca por texto --}}
            <div class="sm:col-span-2 lg:col-span-1">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Buscar</label>
                <div class="relative">
                    <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="busca" value="{{ $filters['busca'] ?? '' }}"
                           placeholder="Espaço, professor ou descrição..."
                           class="w-full pl-8 pr-3 py-2 border border-gray-200 dark:border-gray-600 rounded-lg text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Status</label>
                <select name="status" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    <option value="">Todos</option>
                    @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ ($filters['status'] ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Espaço --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Espaço / Lab</label>
                <select name="space_id" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    <option value="">Todos</option>
                    @foreach($spaces as $s)
                    <option value="{{ $s->id }}" {{ ($filters['space_id'] ?? '') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Período --}}
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Período</label>
                <div class="flex gap-1.5 items-center">
                    <input type="date" name="data_inicio" value="{{ $filters['data_inicio'] ?? '' }}"
                           class="flex-1 border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-2 text-xs dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                    <span class="text-gray-400 text-xs flex-shrink-0">até</span>
                    <input type="date" name="data_fim" value="{{ $filters['data_fim'] ?? '' }}"
                           class="flex-1 border border-gray-200 dark:border-gray-600 rounded-lg px-2 py-2 text-xs dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-etec-main transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Filtrar
            </button>
            @if(array_filter($filters ?? []))
            <a href="{{ route('lab.reservations.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-red-500 transition font-semibold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Limpar filtros
            </a>
            @endif

            {{-- Contagem de resultados --}}
            <span class="ml-auto text-xs text-gray-400">
                {{ $reservations->total() }} reserva(s) encontrada(s)
            </span>
        </div>
    </form>

    {{-- Fila de ações pendentes (Coordenador / Auxiliar) --}}
    @if(isset($pendentes) && $pendentes?->count())
    @php
        $isCoordenador = auth()->user()->is_admin || auth()->user()->hasRole('Coordenador');
        $filaLabel = $isCoordenador
            ? 'Reservas aguardando sua ação'
            : 'Reservas aguardando sua conferência';
        $filaColor = $isCoordenador ? 'blue' : 'orange';
    @endphp
    <div class="bg-{{ $filaColor }}-50 dark:bg-{{ $filaColor }}-900/20 rounded-xl border-2 border-{{ $filaColor }}-200 dark:border-{{ $filaColor }}-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-{{ $filaColor }}-200 dark:border-{{ $filaColor }}-800 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-{{ $filaColor }}-500 animate-pulse"></span>
            <h2 class="font-bold text-{{ $filaColor }}-900 dark:text-{{ $filaColor }}-300 text-sm">
                {{ $pendentes->count() }} {{ $filaLabel }}
            </h2>
        </div>
        <div class="divide-y divide-{{ $filaColor }}-100 dark:divide-{{ $filaColor }}-800">
            @foreach($pendentes as $r)
            <a href="{{ route('lab.reservations.show', $r) }}"
               class="flex items-center gap-4 px-6 py-4 hover:bg-{{ $filaColor }}-100 dark:hover:bg-{{ $filaColor }}-900/40 transition">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $r->space->name ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $r->reservation_date->format('d/m/Y') }}
                        {{ $r->start_time ? '• '.substr($r->start_time,0,5) : '' }}
                        • Prof. {{ $r->user->name ?? '—' }}
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-{{ $filaColor }}-100 text-{{ $filaColor }}-700 flex-shrink-0 whitespace-nowrap">
                    {{ $r->status_label }}
                </span>
                <svg class="w-4 h-4 text-{{ $filaColor }}-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Espaço</th>
                    <th class="px-6 py-3 text-left">Data</th>
                    <th class="px-6 py-3 text-left">Horário</th>
                    @if(auth()->user()?->is_admin)<th class="px-6 py-3 text-left">Professor</th>@endif
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($reservations as $r)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $r->space->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $r->reservation_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ substr($r->start_time,0,5) }}{{ $r->end_time ? ' – '.substr($r->end_time,0,5) : '' }}</td>
                    @if(auth()->user()?->is_admin)<td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $r->user->name ?? '—' }}</td>@endif
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold
                            {{ match($r->status) {
                                'aprovada'    => 'bg-blue-100 text-blue-700',
                                'em_execucao' => 'bg-yellow-100 text-yellow-700',
                                'concluida','finalizada' => 'bg-green-100 text-green-700',
                                'recusada'    => 'bg-red-100 text-red-700',
                                'aguardando_conferencia' => 'bg-orange-100 text-orange-700',
                                default       => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ $r->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right">
                        <a href="{{ route('lab.reservations.show', $r) }}" class="text-etec-main dark:text-etec-accent hover:underline text-xs font-semibold">Ver</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">Nenhuma reserva encontrada.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $reservations->links() }}</div>
        @endif
    </div>
</div>
@endsection
