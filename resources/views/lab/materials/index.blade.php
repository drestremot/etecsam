@extends('admin.layouts.app')
@section('title', 'Materiais')
@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Materiais</h1>
        <a href="{{ route('lab.materials.create') }}"
           class="inline-flex items-center gap-2 bg-etec-dark text-white px-4 py-2.5 rounded-lg hover:bg-etec-main transition font-semibold text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Novo Material
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 dark:bg-gray-700 text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                <tr>
                    <th class="px-6 py-3 text-left">Material</th>
                    <th class="px-6 py-3 text-center">Estoque</th>
                    <th class="px-6 py-3 text-left">Unidade</th>
                    <th class="px-6 py-3 text-left">Patrimônio</th>
                    <th class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                @forelse($materials as $m)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">
                    <td class="px-6 py-3">
                        <div class="flex items-center gap-3">
                            @if($m->photo)
                                <img src="{{ Storage::url($m->photo) }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-etec-light flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-etec-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $m->name }}</p>
                                @if($m->description)
                                <p class="text-xs text-gray-400 truncate">{{ $m->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-3 text-center">
                        <span class="font-bold {{ $m->stock_quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $m->stock_quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400">{{ $m->unit ?? '—' }}</td>
                    <td class="px-6 py-3 text-gray-500 dark:text-gray-400 font-mono text-xs">{{ $m->patrimony_number ?? '—' }}</td>
                    <td class="px-6 py-3 text-right">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('lab.materials.edit', $m) }}" class="text-etec-main dark:text-etec-accent hover:underline text-xs font-semibold">Editar</a>
                            <form action="{{ route('lab.materials.destroy', $m) }}" method="POST" onsubmit="return confirm('Excluir este material?')">
                                @csrf @method('DELETE')
                                <button class="text-red-500 hover:underline text-xs font-semibold">Excluir</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">Nenhum material cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($materials->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">{{ $materials->links() }}</div>
        @endif
    </div>
</div>
@endsection
