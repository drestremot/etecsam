@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Painel</a>
                    <span>/</span>
                    <span class="text-gray-800 font-semibold">Horários de Trabalho</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-md shadow-indigo-200">
                        📅
                    </span>
                    <span>Grade de Horários & Aulas</span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5">
                    Gerenciamento das grades horárias, cursos, disciplinas, coordenações e expedientes das unidades escolares.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.work-schedules.print') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-teal-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimir / Publicação Alunos</span>
                </a>

                <a href="{{ route('admin.work-schedules.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Montar Grade Interativa</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-xs font-medium flex items-center gap-3 shadow-2xs">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-medium space-y-1 shadow-2xs">
                <div class="font-bold">Atenção:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3.5">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Total de Horários</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 mt-1">{{ $stats['total_schedules'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Professores & Colaboradores</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-indigo-700 mt-1">{{ $stats['scheduled_users'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Unidades Escolares</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-teal-700 mt-1">{{ $stats['active_units'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Cursos Ativos</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-purple-700 mt-1">{{ $stats['active_courses'] ?? \App\Models\Course::where('is_active', true)->count() }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.work-schedules.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Docente / Usuário</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        <option value="">Todos os Usuários</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Filtrar Curso</label>
                    <select name="course_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        <option value="">Todos os Cursos</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Unidade Escolar</label>
                    <select name="unit_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        <option value="">Todas as Unidades</option>
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ request('unit_id') == $un->id ? 'selected' : '' }}>{{ $un->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Dia da Semana</label>
                    <select name="day_of_week" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        <option value="">Todos os Dias</option>
                        @foreach(\App\Models\WorkSchedule::getDaysList() as $num => $name)
                            <option value="{{ $num }}" {{ request('day_of_week') !== null && request('day_of_week') != '' && request('day_of_week') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Tipo de Horário</label>
                    <select name="schedule_type" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        <option value="">Todos os Tipos</option>
                        <option value="class" {{ request('schedule_type') === 'class' ? 'selected' : '' }}>Aula (Docente)</option>
                        <option value="coordination" {{ request('schedule_type') === 'coordination' ? 'selected' : '' }}>Coordenação</option>
                        <option value="administrative" {{ request('schedule_type') === 'administrative' ? 'selected' : '' }}>Administrativo / Expediente</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-800 transition cursor-pointer">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl border border-gray-300 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Table of Schedules -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden" x-data="adminTable()">
            <!-- Search and Per Page bar -->
            <div class="px-5 py-3.5 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="q" @input="search()" type="text" placeholder="Filtrar por nome, disciplina, curso, turma, dia ou unidade..."
                           class="flex-1 text-xs sm:text-sm border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                    <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-bold">limpar</button>
                </div>
                @include('admin.partials.per-page-selector')
            </div>
            <!-- Bulk Action Bar -->
            <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-900 font-medium">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
                    <span>horário(s) selecionado(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('admin.work-schedules.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover os horários selecionados da grade?');">
                        @csrf
                        <input type="hidden" name="action" value="delete">
                        <template x-for="id in selected" :key="'del-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Excluir da Grade
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
                                <input type="checkbox" data-bulk-master @click.prevent="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3 min-w-[110px]">Dia</th>
                            <th class="px-3 py-3 min-w-[190px]">Professor / Colaborador</th>
                            <th class="px-3 py-3 min-w-[240px]">Curso & Disciplina</th>
                            <th class="px-3 py-3 text-center min-w-[130px]">Unidade</th>
                            <th class="px-3 py-3 min-w-[90px]">Turno</th>
                            <th class="px-3 py-3 text-center min-w-[100px]">Horário</th>
                            <th class="px-3 py-3 text-center min-w-[90px]">Intervalo</th>
                            <th class="px-3 py-3 text-center min-w-[80px]">Tolerância</th>
                            <th class="px-3.5 py-3 text-right min-w-[80px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedules as $sched)
                        @php
                            $teacherColor = $sched->teacher_color;
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition"
                            data-row="{{ strtolower($sched->user->name . ' ' . $sched->user->email . ' ' . $sched->unit->name . ' ' . $sched->day_name . ' ' . $sched->course_name . ' ' . ($sched->course?->title ?? '') . ' ' . $sched->subject_name . ' ' . $sched->class_name) }}">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $sched->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-[11px] font-semibold {{ $sched->day_badge_class }}">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $sched->day_color_hex }}"></span>
                                    <span>{{ $sched->day_short }} • {{ $sched->day_name }}</span>
                                </span>
                            </td>

                            <td class="px-3 py-2.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $teacherColor['dot'] }};" title="{{ $teacherColor['name'] }}"></span>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 truncate max-w-[180px]" title="{{ $sched->user->name }}">
                                            {{ $sched->user->name }}
                                        </div>
                                        <div class="text-[10.5px] text-gray-500 truncate max-w-[180px]">
                                            {{ $sched->user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-3 py-2.5">
                                @if($sched->isCoordinationSchedule())
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-purple-50 border border-purple-200 px-2 py-1 text-purple-700 font-semibold text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Horário de Coordenação</span>
                                    </div>
                                    @if($sched->shift_name)
                                        <div class="text-[10px] text-gray-500 mt-0.5 truncate">{{ $sched->shift_name }}</div>
                                    @endif
                                @elseif($sched->isAdministrativeSchedule())
                                    <div class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 border border-slate-200 px-2 py-1 text-slate-700 font-semibold text-[11px]">
                                        <svg class="w-3.5 h-3.5 text-slate-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span>Expediente Administrativo</span>
                                    </div>
                                    @if($sched->shift_name)
                                        <div class="text-[10px] text-gray-500 mt-0.5 truncate">{{ $sched->shift_name }}</div>
                                    @endif
                                @else
                                    {{-- Docente / Aula --}}
                                    <div class="space-y-0.5">
                                        @if($sched->course_name || $sched->course)
                                            <div>
                                                <span class="inline-flex items-center gap-1 rounded bg-indigo-50 border border-indigo-200 px-1.5 py-0.2 text-[10px] font-bold text-indigo-900 leading-tight">
                                                    🎓 {{ $sched->course_name ?? $sched->course->title }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($sched->subject_name)
                                            <div class="font-semibold text-gray-900 flex items-center gap-1.5 flex-wrap">
                                                <span class="truncate max-w-[210px]" title="{{ $sched->subject_name }}">{{ $sched->subject_name }}</span>
                                                @if($sched->division === 'A' || str_contains(strtoupper($sched->subject_name), '(A)') || str_contains(strtoupper($sched->subject_name), 'TURMA A'))
                                                    <span class="rounded bg-sky-100 text-sky-800 border border-sky-300 px-1.5 py-0.2 text-[9px] font-extrabold">Turma (A)</span>
                                                @elseif($sched->division === 'B' || str_contains(strtoupper($sched->subject_name), '(B)') || str_contains(strtoupper($sched->subject_name), 'TURMA B'))
                                                    <span class="rounded bg-orange-100 text-orange-800 border border-orange-300 px-1.5 py-0.2 text-[9px] font-extrabold">Turma (B)</span>
                                                @endif
                                            </div>
                                        @else
                                            <div class="text-gray-400 italic text-[11px]">Disciplina não especificada</div>
                                        @endif

                                        <div class="flex items-center gap-2 mt-0.5 text-[10.5px] text-gray-500">
                                            @if($sched->class_name)
                                                <span class="font-medium text-indigo-700 bg-indigo-50 px-1.5 py-0.2 rounded border border-indigo-100">
                                                    {{ $sched->class_name }}
                                                </span>
                                            @endif
                                            @if($sched->classroom)
                                                <span class="text-gray-600 bg-gray-100 px-1.5 py-0.2 rounded">
                                                    {{ $sched->classroom }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </td>

                            <td class="px-3 py-2.5 text-center">
                                <span class="rounded-lg bg-gray-100 px-2 py-1 text-[11px] font-medium text-gray-700 truncate max-w-[130px] inline-block" title="{{ $sched->unit->name }} ({{ $sched->unit->city }})">
                                    {{ $sched->unit->name }}
                                </span>
                            </td>

                            <td class="px-3 py-2.5">
                                <span class="text-gray-700 font-medium text-[11px]">
                                    {{ $sched->shift_name ?? '—' }}
                                </span>
                            </td>

                            <td class="px-3 py-2.5 text-center font-mono font-bold text-gray-900 whitespace-nowrap text-[11px]">
                                {{ $sched->formatted_start_time }} - {{ $sched->formatted_end_time }}
                            </td>

                            <td class="px-3 py-2.5 text-center text-gray-600 font-mono text-[11px]">
                                @if($sched->break_start_time && $sched->break_end_time)
                                    <span class="rounded bg-amber-50 text-amber-700 px-1.5 py-0.5 text-[10.5px] border border-amber-200">
                                        {{ substr($sched->break_start_time, 0, 5) }} - {{ substr($sched->break_end_time, 0, 5) }}
                                    </span>
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>

                            <td class="px-3 py-2.5 text-center">
                                <span class="rounded bg-blue-50 px-2 py-0.5 text-[11px] font-medium text-blue-700">
                                    ±{{ $sched->tolerance_minutes }} min
                                </span>
                            </td>

                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.work-schedules.edit', $sched) }}" class="rounded-lg bg-gray-100 hover:bg-gray-200 p-1.5 text-gray-700 transition" title="Editar">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.work-schedules.destroy', $sched) }}" method="POST" class="inline" onsubmit="return confirm('Remover este horário da grade?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-red-50 hover:bg-red-100 p-1.5 text-red-600 transition" title="Excluir">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="py-8 text-center text-gray-400">
                                Nenhum horário de trabalho ou aula encontrado para os filtros selecionados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Client-side pagination container -->
            <div id="admin-table-pagination-container"></div>
        </div>

    </div>
</div>
@endsection
