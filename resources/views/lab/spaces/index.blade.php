@extends('admin.layouts.app')
@section('title', 'Espaços Didáticos')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Espaços Didáticos</h1>
        <a href="{{ route('lab.spaces.create') }}" class="inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2.5 rounded-lg hover:bg-etec-main transition font-semibold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Espaço
        </a>
    </div>
    @if(session('success'))<div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>@endif
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Espaço</th>
                    <th class="px-6 py-3 text-left">Laboratório vinculado</th>
                    <th class="px-6 py-3 text-left">Auxiliar</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($spaces as $s)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3">
                        <p class="font-medium text-gray-900 dark:text-white">{{ $s->name }}</p>
                        @if($s->description)<p class="text-xs text-gray-400 truncate max-w-xs">{{ $s->description }}</p>@endif
                    </td>
                    <td class="px-6 py-3">
                        @if($s->laboratory)
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-etec-dark bg-etec-light px-2.5 py-1 rounded-full">
                                ✓ {{ $s->laboratory->name }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">Não vinculado</span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $s->auxiliar->name ?? '—' }}</td>
                    <td class="px-6 py-3 text-right flex justify-end gap-3">
                        <a href="{{ route('lab.spaces.edit', $s) }}" class="text-etec-main dark:text-etec-accent hover:underline text-xs font-semibold">Editar</a>
                        <form action="{{ route('lab.spaces.destroy', $s) }}" method="POST" onsubmit="return confirm('Excluir este espaço?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs font-semibold">Excluir</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">Nenhum espaço cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
