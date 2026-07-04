@extends('admin.layouts.app')
@section('title', 'Histórico de Reservas')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Histórico de Reservas</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Atividades concluídas e finalizadas</p>
        </div>
        <a href="{{ route('lab.reservations.index') }}" class="text-sm text-etec-main dark:text-etec-accent hover:underline font-semibold">← Reservas ativas</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">#</th>
                    <th class="px-6 py-3 text-left">Espaço</th>
                    <th class="px-6 py-3 text-left">Professor</th>
                    <th class="px-6 py-3 text-left">Data</th>
                    <th class="px-6 py-3 text-left">Finalizada em</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($reservations as $r)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3 text-gray-400 font-mono text-xs">{{ $r->id }}</td>
                    <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ $r->space->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $r->user->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $r->reservation_date->format('d/m/Y') }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400">
                        {{ $r->finalized_at ? $r->finalized_at->format('d/m/Y H:i') : '—' }}
                    </td>
                    <td class="px-6 py-3">
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                            {{ $r->status_label }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-right flex justify-end gap-3">
                        <a href="{{ route('lab.reservations.show', $r) }}" class="text-etec-main dark:text-etec-accent hover:underline text-xs font-semibold">Ver</a>
                        <a href="{{ route('lab.reservations.pdf', $r) }}" target="_blank" class="text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs font-semibold">PDF</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">Nenhuma reserva concluída ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reservations->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $reservations->links() }}</div>
        @endif
    </div>
</div>
@endsection
