@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.courses.index') }}" class="hover:text-indigo-600 transition">Cursos Técnicos</a>
                    <span>/</span>
                    <span class="text-gray-800">{{ $course->title }}</span>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Grade & Professores</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>{{ $course->title }}</span>
                    <span class="rounded-xl bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700 normal-case tracking-normal">{{ $subjects->count() }} disciplinas</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-0.5">
                    Associação de professores responsáveis por cada disciplina, carga horária e planos de trabalho docente (PTD)
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar aos Cursos</span>
                </a>
                <a href="{{ route('admin.courses.subjects.create', $course) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-indigo-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Nova Disciplina</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Card Resumo do Curso -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Modalidade</span>
                    <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ $course->type ?? 'Geral' }}</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Unidade de Ensino</span>
                    <span class="text-sm font-bold text-gray-800 mt-0.5 block">{{ $course->unit?->name ?? 'Sede' }}</span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Coordenação Técnica</span>
                    <span class="text-sm font-bold text-gray-800 mt-0.5 block">
                        {{ $course->technicalCoordinators->pluck('name')->implode(', ') ?: '—' }}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Status do Curso</span>
                    <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-0.5 rounded-full text-xs font-bold {{ $course->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $course->is_active ? '● Ativo' : '● Inativo' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Subjects Table Container -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden" x-data="adminTable()">
            <!-- Search and Per Page bar -->
            <div class="px-5 py-3.5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="q" @input="search()" type="text" placeholder="Buscar disciplina, professor responsável, semestre..."
                           class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                    <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">limpar</button>
                </div>
                @include('admin.partials.per-page-selector')
            </div>

            <!-- Bulk Action Bar -->
            <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-900 font-medium">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
                    <span>disciplina(s) selecionada(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.courses.subjects.bulk-action', $course) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir as disciplinas selecionadas?');">
                        @csrf
                        <input type="hidden" name="action" value="delete">
                        <template x-for="id in selected" :key="'del-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Excluir Selecionadas
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
                            <input type="checkbox" data-bulk-master @click="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                        </th>
                            <th @click="sort('disc')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[200px]">
                                Disciplina <span class="ml-1 text-gray-400" x-text="icon('disc')"></span>
                            </th>
                            <th @click="sort('sem')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[120px]">
                                Semestre / Período <span class="ml-1 text-gray-400" x-text="icon('sem')"></span>
                            </th>
                            <th class="px-3 py-3 min-w-[80px]">Carga Horária</th>
                            <th class="px-3 py-3 min-w-[200px]">Professor Responsável</th>
                            <th class="px-3 py-3 min-w-[80px]">PTD</th>
                            <th class="px-3.5 py-3 text-right min-w-[110px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($subjects as $subject)
                        <tr class="hover:bg-gray-50/80 transition"
                            data-row="{{ strtolower($subject->name . ' ' . ($subject->teacher?->name ?? '') . ' ' . ($subject->semester ?? '')) }}"
                            data-disc="{{ strtolower($subject->name) }}"
                            data-prof="{{ strtolower($subject->teacher?->name ?? '') }}"
                            data-sem="{{ strtolower($subject->semester ?? '') }}">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $subject->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-3.5 py-2.5 font-medium text-gray-900 text-xs sm:text-[12.5px] leading-snug break-words max-w-[220px]" title="{{ $subject->name }}">
                                <span>{{ $subject->name }}</span>
                            </td>
                            <td class="px-3 py-2.5 text-gray-600 font-normal">
                                <span class="rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700">
                                    {{ $subject->semester ?: 'Não definido' }}
                                </span>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[11px] font-medium text-blue-700 border border-blue-200">
                                    {{ $subject->workload }}h
                                </span>
                            </td>
                            <td class="px-3 py-2.5">
                                <!-- Quick Inline Teacher Association -->
                                <form action="{{ route('admin.courses.subjects.teacher', [$course, $subject]) }}" method="POST" class="flex items-center gap-1.5">
                                    @csrf @method('PATCH')
                                    <select name="teacher_id" onchange="this.form.submit()"
                                            class="rounded-lg border border-gray-300 bg-white px-2.5 py-1 text-xs text-gray-800 shadow-2xs focus:outline-none focus:ring-1 focus:ring-indigo-400 min-w-[12rem] max-w-[16rem]">
                                        <option value="">— Selecionar Professor —</option>
                                        @foreach($teachers as $t)
                                            <option value="{{ $t->id }}" {{ $subject->teacher_id == $t->id ? 'selected' : '' }}>
                                                {{ $t->name }}{{ $t->specialty ? ' ('.$t->specialty.')' : '' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <noscript>
                                        <button type="submit" class="rounded-lg bg-indigo-600 px-2 py-0.5 text-[11px] font-semibold text-white">OK</button>
                                    </noscript>
                                </form>
                            </td>
                            <td class="px-3 py-2.5">
                                @if($subject->ptd_file)
                                    <a href="{{ $subject->ptd_file }}" target="_blank" class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span>PTD</span>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">—</span>
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.courses.subjects.edit', [$course, $subject]) }}" class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 hover:bg-gray-200 transition">
                                        Editar
                                    </a>
                                    <form action="{{ route('admin.courses.subjects.destroy', [$course, $subject]) }}" method="POST" onsubmit="return confirm('Remover a disciplina \'{{ addslashes($subject->name) }}\'?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-50 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-100 transition">
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <p class="font-bold text-base mb-1">Nenhuma disciplina cadastrada neste curso.</p>
                                <p class="text-xs text-gray-500 mb-3">Adicione as matérias da grade curricular para associar os professores.</p>
                                <a href="{{ route('admin.courses.subjects.create', $course) }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-sm hover:bg-indigo-500 transition">
                                    + Cadastrar Primeira Disciplina
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @include('admin.partials.pagination-footer')
        </div>

    </div>
</div>

@endsection
