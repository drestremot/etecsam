@extends('admin.layouts.app')
@section('page-title', 'Parceiros')

@section('header-actions')
    <a href="{{ route('admin.partners.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Novo Parceiro
    </a>
@endsection

@section('content')
@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" x-data="adminTable()">

    <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input x-model="q" @input="search()" type="text" placeholder="Buscar por nome, site..."
               class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
        <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">✕ limpar</button>
    </div>

    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Logo</th>
                <th @click="sort('nome')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Nome <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase hidden md:table-cell">Site</th>
                <th @click="sort('status')" class="px-4 py-3 text-center text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                </th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($partners as $partner)
            <tr class="hover:bg-gray-50 transition {{ !$partner->active ? 'opacity-60' : '' }}"
                data-row="{{ strtolower($partner->name . ' ' . $partner->website) }}"
                data-active="{{ $partner->active ? '1' : '0' }}"
                data-nome="{{ strtolower($partner->name) }}"
                data-status="{{ $partner->active ? 'ativo' : 'inativo' }}">
                <td class="px-4 py-3">
                    @if($partner->logo)
                        <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}" class="h-10 w-auto object-contain">
                    @else
                        <div class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-sm">{{ strtoupper(substr($partner->name,0,2)) }}</div>
                    @endif
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $partner->name }}</td>
                <td class="px-4 py-3 hidden md:table-cell">
                    @if($partner->website)
                        <a href="{{ $partner->website }}" target="_blank" class="text-blue-600 hover:underline text-xs">{{ $partner->website }}</a>
                    @endif
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $partner->active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $partner->active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <form action="{{ route('admin.partners.toggle', $partner) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="{{ $partner->active ? 'Desativar' : 'Ativar' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition {{ $partner->active ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                @if($partner->active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('admin.partners.edit', $partner) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('admin.partners.destroy', $partner) }}"
                              onsubmit="return confirm('Remover \'{{ addslashes($partner->name) }}\'?')" class="inline">
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
            <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">Nenhum parceiro cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.table-script')
@endsection
