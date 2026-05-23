@extends('admin.layouts.app')
@section('page-title', 'Agenda / Eventos')

@section('header-actions')
    <a href="{{ route('admin.events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Evento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Data</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Local</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $event->title }}</td>
                <td class="px-4 py-3 text-gray-500">
                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                    @if($event->end_date && $event->end_date != $event->start_date)
                        → {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $event->location ?? '—' }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.events.edit', $event) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum evento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $events->links() }}</div>
</div>
@endsection
