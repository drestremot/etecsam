@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Cursos Técnicos</span>
                    <span class="rounded-xl bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 normal-case tracking-normal">{{ $courses->total() }} cadastrados</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Gestão de cursos, modalidades (M-Tec, Noturno, AMS), matrizes e coordenações</p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-amber-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Novo Curso</span>
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
            <!-- Search bar -->
            <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-3 bg-gray-50/50">
                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input x-model="q" @input="search()" type="text" placeholder="Buscar por título, tipo, unidade ou coordenador..."
                       class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">limpar</button>
            </div>

            <!-- Bulk Action Bar -->
            <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-900 font-medium">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
                    <span>item(ns) selecionado(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.courses.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="activate">
                        <template x-for="id in selected" :key="'act-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Ativar
                        </button>
                    </form>
                    <form action="{{ route('admin.courses.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="deactivate">
                        <template x-for="id in selected" :key="'deact-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Desativar
                        </button>
                    </form>
                    <form action="{{ route('admin.courses.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir os cursos selecionados?');">
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
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-50/90 text-[11px] font-semibold uppercase text-gray-500 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-3 py-3 w-10 text-center">
                                <input type="checkbox" x-model="allSelected" @change="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </th>
                            <th @click="sort('titulo')" class="px-3.5 py-3 text-left cursor-pointer hover:bg-gray-100 select-none min-w-[200px]">
                                Curso <span class="ml-1 text-gray-400" x-text="icon('titulo')"></span>
                            </th>
                            <th @click="sort('tipo')" class="px-3 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[100px]">
                                Tipo <span class="ml-1 text-gray-400" x-text="icon('tipo')"></span>
                            </th>
                            <th @click="sort('unidade')" class="px-3 py-3 text-center cursor-pointer hover:bg-gray-100 select-none min-w-[130px]">
                                Unidade <span class="ml-1 text-gray-400" x-text="icon('unidade')"></span>
                            </th>
                            <th class="px-3 py-3 min-w-[150px]">Coordenação</th>
                            <th @click="sort('status')" class="px-3 py-3 text-center cursor-pointer hover:bg-gray-100 select-none min-w-[80px]">
                                Status <span class="ml-1 text-gray-400" x-text="icon('status')"></span>
                            </th>
                            <th class="px-3.5 py-3 text-right min-w-[190px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($courses as $course)
                        <tr class="hover:bg-gray-50/80 transition {{ !$course->is_active ? 'opacity-60' : '' }}"
                            data-row="{{ strtolower($course->title . ' ' . $course->type . ' ' . ($course->unit?->name ?? '') . ' ' . $course->technicalCoordinators->pluck('name')->implode(' ')) }}"
                            data-active="{{ $course->is_active ? '1' : '0' }}"
                            data-titulo="{{ strtolower($course->title) }}"
                            data-tipo="{{ strtolower($course->type) }}"
                            data-unidade="{{ strtolower($course->unit?->name ?? '') }}"
                            data-status="{{ $course->is_active ? 'ativo' : 'inativo' }}">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $course->id }}" x-model="selected" @change="updateSelectAll()" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-3.5 py-2.5 text-left font-bold text-gray-900 text-xs sm:text-[13px] leading-snug break-words max-w-[240px]" title="{{ $course->title }}">
                                {{ $course->title }}
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="rounded-lg bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700">{{ $course->type }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-center text-gray-600 font-normal text-[11px] leading-snug break-words max-w-[150px]">
                                {{ $course->unit?->name ?? '—' }}
                            </td>
                            <td class="px-3 py-2.5 text-gray-600">
                                @forelse($course->technicalCoordinators as $c)
                                    <span class="block text-xs font-medium text-gray-800 truncate max-w-[180px]">{{ $c->name }}</span>
                                @empty
                                    <span class="text-gray-400 text-xs">—</span>
                                @endforelse
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <form action="{{ route('admin.courses.toggle', $course) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-2.5 py-0.5 rounded-full text-[11px] font-medium transition shadow-2xs {{ $course->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $course->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.courses.subjects.index', $course) }}" class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-medium text-indigo-700 hover:bg-indigo-100 border border-indigo-200 transition shadow-2xs">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                        <span>Disciplinas</span>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course) }}" class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100 transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum curso cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($courses->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">{{ $courses->links() }}</div>
            @endif
        </div>

    </div>
</div>

@endsection
