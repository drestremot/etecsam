@extends('admin.layouts.app')
@section('page-title', 'Professores e Funcionários')

@section('header-actions')
    <a href="{{ route('admin.teachers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">

    {{-- Busca --}}
    <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input x-model="q" @input="search()" type="text" placeholder="Buscar por nome, cargo, e-mail..."
               class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
        <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">✕ limpar</button>
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th @click="sort('nome')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Nome <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                </th>
                <th @click="sort('cargo')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Cargo / Função <span class="ml-1 text-gray-400" x-text="icon('cargo')"></span>
                </th>
                <th @click="sort('email')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    E-mail <span class="ml-1 text-gray-400" x-text="icon('email')"></span>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Telefone</th>
                <th class="px-4 py-3 w-24"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($teachers as $teacher)
            <tr class="hover:bg-gray-50"
                data-row="{{ strtolower($teacher->name . ' ' . $teacher->role . ' ' . $teacher->email) }}"
                data-active="1"
                data-nome="{{ strtolower($teacher->name) }}"
                data-cargo="{{ strtolower($teacher->role) }}"
                data-email="{{ strtolower($teacher->email ?? '') }}">
                <td class="px-4 py-3 font-medium text-gray-800">
                    @if($teacher->photo)
                        <img src="{{ photo_url($teacher->photo) }}" class="w-8 h-8 rounded-full object-cover inline-block mr-2"
                             onerror="this.style.display='none'">
                    @endif
                    {{ $teacher->name }}
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $teacher->role }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $teacher->email ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $teacher->phone ?? '—' }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.teachers.edit', $teacher) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover {{ addslashes($teacher->name) }}?')">
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
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $teachers->links() }}</div>
</div>
@include('admin.partials.table-script')
@endsection
