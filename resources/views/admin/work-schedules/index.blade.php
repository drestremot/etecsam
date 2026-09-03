@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Grade de Horários</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 flex items-center gap-2.5">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Grade de Horários dos Professores & Colaboradores</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Definição de turnos, jornadas e unidades escolares onde cada docente leciona durante a semana
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.work-schedules.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Cadastrar Horário</span>
                </a>

                <a href="{{ route('admin.timeclock.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Painel do Ponto</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-medium text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-xs sm:text-sm font-medium shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Jornadas Cadastradas</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900 mt-1">{{ $stats['total_schedules'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Professores com Grade</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-indigo-700 mt-1">{{ $stats['scheduled_users'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Unidades Escolares</span>
                <div class="text-xl sm:text-2xl font-semibold tracking-tight text-teal-700 mt-1">{{ $stats['active_units'] }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.work-schedules.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Professor / Funcionário</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todos os Professores</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Unidade Escolar</label>
                    <select name="unit_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todas as Unidades</option>
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ request('unit_id') == $un->id ? 'selected' : '' }}>{{ $un->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-600 uppercase mb-1">Dia da Semana</label>
                    <select name="day_of_week" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todos os Dias</option>
                        @foreach(\App\Models\WorkSchedule::getDaysList() as $num => $name)
                            <option value="{{ $num }}" {{ request('day_of_week') !== null && request('day_of_week') != '' && request('day_of_week') == $num ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-800 transition">
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
                    <input x-model="q" @input="search()" type="text" placeholder="Filtrar por nome, email, dia ou unidade..."
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
                                <input type="checkbox" data-bulk-master :checked="allSelected" @click.prevent="toggleSelectAll()" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </th>
                            <th class="px-3.5 py-3 min-w-[100px]">Dia</th>
                            <th class="px-3 py-3 min-w-[180px]">Professor / Colaborador</th>
                            <th class="px-3 py-3 text-center min-w-[130px]">Unidade</th>
                            <th class="px-3 py-3 min-w-[110px]">Turno</th>
                            <th class="px-3 py-3 text-center min-w-[100px]">Horário</th>
                            <th class="px-3 py-3 text-center min-w-[90px]">Intervalo</th>
                            <th class="px-3 py-3 text-center min-w-[80px]">Tolerância</th>
                            <th class="px-3.5 py-3 text-right min-w-[80px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedules as $sched)
                        <tr class="hover:bg-gray-50/80 transition"
                            data-row="{{ strtolower($sched->user->name . ' ' . $sched->user->email . ' ' . $sched->unit->name . ' ' . $sched->day_name) }}">
                            <td class="px-3 py-2.5 text-center">
                                <input type="checkbox" value="{{ $sched->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                            </td>
                            <td class="px-3.5 py-2.5 whitespace-nowrap">
                                <span class="inline-flex items-center rounded-md bg-indigo-50 border border-indigo-200/80 px-2 py-0.5 text-[11px] font-medium text-indigo-700">
                                    {{ $sched->day_short }} • {{ $sched->day_name }}
                                </span>
                            </td>

                            <td class="px-3 py-2.5">
                                <div class="font-semibold text-gray-900 truncate max-w-[200px]" title="{{ $sched->user->name }}">{{ $sched->user->name }}</div>
                                <div class="text-[11px] text-gray-500 font-normal truncate max-w-[200px]">{{ $sched->user->email }}</div>
                            </td>

                            <td class="px-3 py-2.5 text-center">
                                <span class="inline-block rounded-md bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-700 leading-snug break-words max-w-[140px]">
                                    {{ $sched->unit->name }}
                                </span>
                            </td>

                            <td class="px-3 py-2.5">
                                <div class="font-normal text-gray-700 truncate max-w-[120px]">{{ $sched->shift_name ?: 'Jornada Padrão' }}</div>
                            </td>

                            <td class="px-3 py-2.5 text-center font-mono font-medium text-indigo-950 whitespace-nowrap">
                                {{ $sched->formatted_schedule }}
                            </td>

                            <td class="px-3 py-2.5 text-center font-mono text-gray-600 whitespace-nowrap">
                                @if($sched->break_start_time)
                                    {{ substr($sched->break_start_time, 0, 5) }} às {{ substr($sched->break_end_time, 0, 5) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-3 py-2.5 text-center whitespace-nowrap">
                                <span class="rounded-full bg-amber-50 border border-amber-200 px-2 py-0.2 text-[10.5px] font-medium text-amber-800">
                                    {{ $sched->tolerance_minutes }} min
                                </span>
                            </td>

                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.work-schedules.edit', $sched) }}" class="rounded-lg bg-gray-100 hover:bg-gray-200 p-1 text-gray-700 transition" title="Editar">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>

                                    <form action="{{ route('admin.work-schedules.destroy', $sched) }}" method="POST" class="inline" onsubmit="return confirm('Remover este horário da grade?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg bg-rose-50 hover:bg-rose-100 p-1.5 text-rose-600 transition" title="Excluir">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-sm font-normal text-gray-500">Nenhum horário cadastrado para os filtros selecionados.</p>
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
