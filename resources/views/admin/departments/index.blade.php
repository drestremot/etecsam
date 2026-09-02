@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Departamentos & Coordenações</span>
                    <span class="rounded-xl bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 normal-case tracking-normal">{{ count($departments) }} cadastrados</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Gestão de coordenações pedagógicas, secretarias e responsáveis de área</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-purple-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-purple-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Departamento</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden" x-data="adminTable()">
            <!-- Search and Per Page bar -->
            <div class="px-5 py-3.5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="q" @input="search()" type="text" placeholder="Buscar por departamento, responsável ou e-mail..."
                           class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                    <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">limpar</button>
                </div>
                @include('admin.partials.per-page-selector')
            </div>

            <!-- Bulk Action Bar -->
            <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-900 font-medium">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
                    <span>departamento(s) selecionado(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.departments.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="activate">
                        <template x-for="id in selected" :key="'act-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Ativar
                        </button>
                    </form>
                    <form action="{{ route('admin.departments.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="deactivate">
                        <template x-for="id in selected" :key="'deact-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Desativar
                        </button>
                    </form>
                    <form action="{{ route('admin.departments.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir os departamentos selecionados?');">
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

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50/90 text-[11px] font-semibold uppercase text-gray-500 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-3 py-3 w-10 text-center">
                                <input type="checkbox" :checked="allSelected" @click.prevent="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </th>
                            <th @click="sort('nome')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[200px]">
                                Departamento <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                            </th>
                            <th @click="sort('resp')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[150px]">
                                Responsável <span class="ml-1 text-gray-400" x-text="icon('resp')"></span>
                            </th>
                            <th class="px-3 py-3 min-w-[180px]">Contato</th>
                            <th @click="sort('status')" class="px-3 py-3 text-center cursor-pointer hover:bg-gray-100 select-none min-w-[80px]">
                                Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                            </th>
                            <th class="px-3.5 py-3 text-right min-w-[100px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($departments as $dept)
                        <tr class="hover:bg-gray-50/80 transition {{ !$dept->is_active ? 'opacity-60' : '' }}"
                            data-row="{{ strtolower($dept->name . ' ' . ($dept->responsible?->name ?? '') . ' ' . $dept->email) }}"
                            data-active="{{ $dept->is_active ? '1' : '0' }}"
                            data-nome="{{ strtolower($dept->name) }}"
                            data-resp="{{ strtolower($dept->responsible?->name ?? '') }}"
                            data-status="{{ $dept->is_active ? 'ativo' : 'inativo' }}">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $dept->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-3.5 py-2.5 font-semibold text-gray-900 truncate max-w-[220px]" title="{{ $dept->name }}">
                                {{ $dept->name }}
                            </td>
                            <td class="px-3 py-2.5 text-gray-700 font-medium truncate max-w-[160px]">
                                {{ $dept->responsible?->name ?? '—' }}
                            </td>
                            <td class="px-3 py-2.5 text-gray-500 text-xs">
                                <div class="font-mono text-gray-600 truncate max-w-[180px]">{{ $dept->email ?? '' }}</div>
                                @if($dept->phone) <div class="text-gray-400 text-[11px]">{{ $dept->phone }}</div> @endif
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <form action="{{ route('admin.departments.toggle', $dept) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-2.5 py-0.5 rounded-full text-[11px] font-medium transition shadow-2xs {{ $dept->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $dept->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.departments.edit', $dept) }}" class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100 transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum departamento cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination-footer')
        </div>

    </div>
</div>

@endsection
