@extends('admin.layouts.app')
@section('page-title', 'Agenda / Eventos')

@section('header-actions')
    <a href="{{ route('admin.events.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">

    <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-2">
        <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input x-model="q" @input="search()" type="text" placeholder="Buscar por título, local..."
               class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
        <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">✕ limpar</button>
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th @click="sort('titulo')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Evento <span class="ml-1 text-gray-400" x-text="icon('titulo')"></span>
                </th>
                <th @click="sort('data')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Data <span class="ml-1 text-gray-400" x-text="icon('data')"></span>
                </th>
                <th @click="sort('local')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Local <span class="ml-1 text-gray-400" x-text="icon('local')"></span>
                </th>
                <th @click="sort('status')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none w-28">
                    Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                </th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($events as $event)
            <tr class="hover:bg-gray-50 {{ !$event->is_active ? 'opacity-60' : '' }}"
                data-row="{{ strtolower($event->title . ' ' . ($event->location ?? '')) }}"
                data-active="{{ $event->is_active ? '1' : '0' }}"
                data-titulo="{{ strtolower($event->title) }}"
                data-data="{{ $event->start_date }}"
                data-local="{{ strtolower($event->location ?? '') }}"
                data-status="{{ $event->is_active ? 'ativo' : 'inativo' }}">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $event->title }}</td>
                <td class="px-4 py-3 text-gray-500">
                    {{ \Carbon\Carbon::parse($event->start_date)->format('d/m/Y') }}
                    @if($event->end_date && $event->end_date != $event->start_date)
                        → {{ \Carbon\Carbon::parse($event->end_date)->format('d/m/Y') }}
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $event->location ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $event->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $event->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        {{-- Toggle ativo/inativo --}}
                        <form action="{{ route('admin.events.toggle', $event) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="{{ $event->is_active ? 'Desativar' : 'Ativar' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition {{ $event->is_active ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                @if($event->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        {{-- Editar --}}
                        <a href="{{ route('admin.events.edit', $event) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        {{-- Excluir --}}
                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover \'{{ addslashes($event->title) }}\'?')">
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
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum evento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $events->links() }}</div>
</div>
@include('admin.partials.table-script')
@endsection
