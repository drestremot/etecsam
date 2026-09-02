@extends('admin.layouts.app')
@section('page-title', 'Unidades Escolares')

@section('header-actions')
    <a href="{{ route('admin.units.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Nova</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">

    <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
        <div class="flex items-center gap-2 flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="q" @input="search()" type="text" placeholder="Buscar unidade, cidade, coordenador..."
                   class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
            <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">limpar</button>
        </div>
        @include('admin.partials.per-page-selector')
    </div>

    <!-- Bulk Action Bar -->
    <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2 text-indigo-900 font-medium">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
            <span>unidade(s) selecionada(s)</span>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.units.bulk-action') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="activate">
                <template x-for="id in selected" :key="'act-'+id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                    Ativar
                </button>
            </form>
            <form action="{{ route('admin.units.bulk-action') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="action" value="deactivate">
                <template x-for="id in selected" :key="'deact-'+id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="rounded-lg bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                    Desativar
                </button>
            </form>
            <form action="{{ route('admin.units.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir as unidades selecionadas?');">
                @csrf
                <input type="hidden" name="action" value="delete">
                <template x-for="id in selected" :key="'del-'+id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                    Excluir
                </button>
            </form>
            <button type="button" @click="clearSelection()" class="text-gray-500 hover:text-gray-700 font-medium ml-1">
                Cancelar
            </button>
        </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-xs sm:text-sm">
        <thead class="bg-gray-50 text-[11px] font-semibold text-gray-500 uppercase">
            <tr>
                <th class="px-3 py-3 w-10 text-center">
                    <input type="checkbox" x-ref="selectAllCheckbox" @click="toggleSelectAll($event.target.checked)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                </th>
                <th @click="sort('nome')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none">
                    Unidade <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                </th>
                <th @click="sort('cidade')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none">
                    Cidade <span class="ml-1 text-gray-400" x-text="icon('cidade')"></span>
                </th>
                <th @click="sort('coord')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none">
                    Coordenador <span class="ml-1 text-gray-400" x-text="icon('coord')"></span>
                </th>
                <th @click="sort('status')" class="px-3.5 py-3 text-center cursor-pointer hover:bg-gray-100 select-none">
                    Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                </th>
                <th class="px-3.5 py-3 text-right w-28">Ações</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($units as $unit)
            <tr class="hover:bg-gray-50 {{ !$unit->is_active ? 'opacity-60' : '' }}"
                data-row="{{ strtolower($unit->name . ' ' . $unit->city . ' ' . ($unit->coordinator?->name ?? '')) }}"
                data-active="{{ $unit->is_active ? '1' : '0' }}"
                data-nome="{{ strtolower($unit->name) }}"
                data-cidade="{{ strtolower($unit->city) }}"
                data-coord="{{ strtolower($unit->coordinator?->name ?? '') }}"
                data-status="{{ $unit->is_active ? 'ativo' : 'inativo' }}">
                <td class="px-3 py-2.5 text-center">
                    <input type="checkbox" value="{{ $unit->id }}" data-bulk-item @click="toggleItem('{{ $unit->id }}', $event.target.checked)" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                </td>
                <td class="px-3.5 py-2.5 font-semibold text-gray-900 leading-snug">{{ $unit->name }}</td>
                <td class="px-3.5 py-2.5 text-gray-600">{{ $unit->city }}</td>
                <td class="px-3.5 py-2.5 text-gray-600">{{ $unit->coordinator?->name ?? '—' }}</td>
                <td class="px-3.5 py-2.5 text-center">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $unit->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $unit->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <form action="{{ route('admin.units.toggle', $unit) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="{{ $unit->is_active ? 'Desativar' : 'Ativar' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition {{ $unit->is_active ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                @if($unit->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('admin.units.edit', $unit) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover \'{{ addslashes($unit->name) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Excluir"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhuma unidade cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    @include('admin.partials.pagination-footer')
</div>
@endsection
