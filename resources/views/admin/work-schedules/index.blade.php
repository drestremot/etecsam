@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Grade de Horários</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>📅 Grade de Horários dos Professores & Colaboradores</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Definição de turnos, jornadas e unidades escolares onde cada docente leciona durante a semana
                </p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.work-schedules.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2.5 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Cadastrar Horário de Professor</span>
                </a>

                <a href="{{ route('admin.timeclock.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Painel do Ponto</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-xs sm:text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Jornadas Cadastradas</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mt-1">{{ $stats['total_schedules'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Professores com Grade</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-indigo-700 mt-1">{{ $stats['scheduled_users'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Unidades Escolares</span>
                <div class="text-2xl sm:text-3xl font-bold tracking-tight text-teal-700 mt-1">{{ $stats['active_units'] }}</div>
            </div>
        </div>

        <!-- Filters Bar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.work-schedules.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1">Professor / Funcionário</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todos os Professores</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1">Unidade Escolar</label>
                    <select name="unit_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="">Todas as Unidades</option>
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ request('unit_id') == $un->id ? 'selected' : '' }}>{{ $un->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1">Dia da Semana</label>
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
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 px-4 py-2 text-xs font-bold text-white hover:bg-gray-800 transition">
                        Filtrar
                    </button>
                    <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        <!-- Table of Schedules -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-100/70 text-xs font-semibold uppercase text-gray-600 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-5 py-3">Dia</th>
                            <th class="px-5 py-3">Professor / Colaborador</th>
                            <th class="px-5 py-3">Unidade Escolar</th>
                            <th class="px-5 py-3">Turno / Descrição</th>
                            <th class="px-5 py-3 text-center">Horário Previsto</th>
                            <th class="px-5 py-3 text-center">Intervalo</th>
                            <th class="px-5 py-3 text-center">Tolerância</th>
                            <th class="px-5 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($schedules as $sched)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center rounded-lg bg-indigo-50 border border-indigo-200 px-2.5 py-1 text-xs font-bold text-indigo-700">
                                    {{ $sched->day_short }} • {{ $sched->day_name }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="font-bold text-gray-900">{{ $sched->user->name }}</div>
                                <div class="text-[11px] text-gray-500 font-normal">{{ $sched->user->email }}</div>
                            </td>

                            <td class="px-5 py-3.5">
                                <span class="rounded-lg bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800">
                                    🏢 {{ $sched->unit->name }}
                                </span>
                            </td>

                            <td class="px-5 py-3.5">
                                <div class="font-semibold text-gray-800">{{ $sched->shift_name ?: 'Jornada Padrão' }}</div>
                            </td>

                            <td class="px-5 py-3.5 text-center font-mono font-bold text-indigo-900">
                                {{ $sched->formatted_schedule }}
                            </td>

                            <td class="px-5 py-3.5 text-center font-mono text-gray-600">
                                @if($sched->break_start_time)
                                    {{ substr($sched->break_start_time, 0, 5) }} às {{ substr($sched->break_end_time, 0, 5) }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-5 py-3.5 text-center">
                                <span class="rounded-full bg-amber-50 border border-amber-200 px-2 py-0.5 text-[10.5px] font-bold text-amber-800">
                                    {{ $sched->tolerance_minutes }} min
                                </span>
                            </td>

                            <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.work-schedules.edit', $sched) }}" class="rounded-lg bg-gray-100 hover:bg-gray-200 p-1.5 text-gray-700 transition" title="Editar">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
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
                                <span class="text-3xl block mb-2">📅</span>
                                <p class="text-sm font-medium">Nenhum horário cadastrado para os filtros selecionados.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($schedules->hasPages())
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
