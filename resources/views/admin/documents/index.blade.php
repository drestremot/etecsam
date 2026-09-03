@extends('admin.layouts.app')
@section('page-title', 'Documentos e Downloads')

@section('header-actions')
    <a href="{{ route('admin.documents.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">

    <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
        <div class="flex items-center gap-2 flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="q" @input="search()" type="text" placeholder="Buscar por título, categoria..."
                   class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
            <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">limpar</button>
        </div>
        @include('admin.partials.per-page-selector')
    </div>

    <!-- Bulk Action Bar -->
    <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
        <div class="flex items-center gap-2 text-indigo-900 font-medium">
            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
            <span>documento(s) selecionado(s)</span>
        </div>
        <div class="flex items-center gap-2">
            <form action="{{ route('admin.documents.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir os documentos selecionados?');">
                @csrf
                <input type="hidden" name="action" value="delete">
                <template x-for="id in selected" :key="'del-'+id">
                    <input type="hidden" name="ids[]" :value="id">
                </template>
                <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                    Excluir Selecionados
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
                    <input type="checkbox" data-bulk-master @click.prevent="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                </th>
                <th @click="sort('titulo')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none">
                    Título <span class="ml-1 text-gray-400" x-text="icon('titulo')"></span>
                </th>
                <th @click="sort('categoria')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none">
                    Categoria <span class="ml-1 text-gray-400" x-text="icon('categoria')"></span>
                </th>
                <th class="px-3.5 py-3 text-left">Arquivo / Link</th>
                <th class="px-3.5 py-3 w-24"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($documents as $doc)
            <tr class="hover:bg-gray-50"
                data-row="{{ strtolower($doc->title . ' ' . $doc->category) }}"
                data-active="1"
                data-titulo="{{ strtolower($doc->title) }}"
                data-categoria="{{ strtolower($doc->category) }}">
                <td class="px-3 py-2.5 text-center">
                    <input type="checkbox" value="{{ $doc->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                </td>
                <td class="px-3.5 py-2.5 font-medium text-gray-800 leading-snug">{{ $doc->title }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $doc->category }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    @if(!empty($doc->file_path))
                        <a href="{{ photo_url($doc->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">Arquivo</a>
                    @elseif(!empty($doc->url))
                        <a href="{{ $doc->url }}" target="_blank" class="text-indigo-600 hover:underline truncate block max-w-xs">Link externo</a>
                    @else
                        —
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.documents.edit', $doc) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover \'{{ addslashes($doc->title) }}\'?')">
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
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum documento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    @include('admin.partials.pagination-footer')
</div>
@endsection
