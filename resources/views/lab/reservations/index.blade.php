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

    {{-- Fila do Auxiliar: aguardando conferência --}}
    @if(isset($pendentes) && $pendentes?->count())
    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-xl border-2 border-orange-200 dark:border-orange-800 overflow-hidden">
        <div class="px-6 py-4 border-b border-orange-200 dark:border-orange-800 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
            <h2 class="font-bold text-orange-900 dark:text-orange-300 text-sm">
                {{ $pendentes->count() }} reserva(s) aguardando sua conferência
            </h2>
        </div>
        <div class="divide-y divide-orange-100 dark:divide-orange-800">
            @foreach($pendentes as $r)
            <a href="{{ route('lab.reservations.show', $r) }}"
               class="flex items-center gap-4 px-6 py-4 hover:bg-orange-100 dark:hover:bg-orange-900/40 transition">
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $r->space->name ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        {{ $r->reservation_date->format('d/m/Y') }}
                        {{ $r->start_time ? '• '.substr($r->start_time,0,5) : '' }}
                        • Prof. {{ $r->user->name ?? '—' }}
                    </p>
                </div>
                <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 flex-shrink-0">
                    Aguardando conferência
                </span>
                <svg class="w-4 h-4 text-orange-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
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
