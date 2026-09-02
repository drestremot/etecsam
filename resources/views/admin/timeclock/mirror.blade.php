@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.timeclock.index') }}" class="hover:text-indigo-600 transition">Ponto Eletrônico</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Espelho Mensal</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>📑 Espelho de Ponto Individual</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Extrato consolidado mensal de jornada, horas trabalhadas, atestados médicos e folgas legais
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimir Folha</span>
                </button>

                <a href="{{ route('admin.timeclock.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar ao Radar</span>
                </a>
            </div>
        </div>

        <!-- Filter / Month & Employee Selection -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
            <form method="GET" action="{{ route('admin.timeclock.mirror') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1">Colaborador / Docente</label>
                    <select name="user_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $user->id == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role ?? 'Docente' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1">Mês de Competência</label>
                    <input type="month" name="month" value="{{ $monthYear }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-mono">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-500 transition">
                        Consultar Espelho
                    </button>
                </div>
            </form>
        </div>

        <!-- Month Summary Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Horas Trabalhadas</span>
                <div class="text-2xl font-bold tracking-tight text-emerald-700 font-mono mt-1">{{ $summary['total_worked_hours'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Horas Programadas</span>
                <div class="text-2xl font-bold tracking-tight text-gray-900 font-mono mt-1">{{ $summary['total_expected_hours'] }}</div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Saldo de Horas</span>
                <div class="text-2xl font-bold tracking-tight font-mono mt-1 {{ $summary['balance_minutes'] >= 0 ? 'text-indigo-600' : 'text-rose-600' }}">
                    {{ $summary['balance_minutes'] >= 0 ? '+' : '' }}{{ sprintf('%02dh %02dmin', floor($summary['balance_minutes'] / 60), abs($summary['balance_minutes'] % 60)) }}
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-xs">
                <span class="text-xs font-medium text-gray-500 block">Total de Atrasos</span>
                <div class="text-2xl font-bold tracking-tight text-amber-600 font-mono mt-1">{{ $summary['total_delays_minutes'] }} min</div>
            </div>
        </div>

        <!-- Daily Detailed Mirror Table -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden">
            <div class="px-6 py-4 bg-gray-50/80 border-b border-gray-200 flex items-center justify-between">
                <div class="font-bold text-gray-900 text-sm">
                    Espelho de Ponto • {{ $user->name }} • {{ \Carbon\Carbon::createFromFormat('Y-m', $monthYear)->translatedFormat('F / Y') }}
                </div>
                <span class="text-xs text-gray-500 font-medium">Portaria 671 MTE / Etec SAM</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-gray-100/70 text-xs font-semibold uppercase text-gray-600 border-b border-gray-200 tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Dia</th>
                            <th class="px-4 py-3">Grade Prevista</th>
                            <th class="px-3 py-3 text-center">1ª Entrada</th>
                            <th class="px-3 py-3 text-center">1ª Saída</th>
                            <th class="px-3 py-3 text-center">2ª Entrada</th>
                            <th class="px-3 py-3 text-center">2ª Saída</th>
                            <th class="px-4 py-3 text-center">Horas Feitas</th>
                            <th class="px-4 py-3">Ocorrências / Integrações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 font-mono">
                        @foreach($days as $d)
                        @php
                            $isWeekend = in_array($d['date']->dayOfWeek, [0, 6]);
                            $hasPunches = $d['raw_punches']->count() > 0;
                        @endphp
                        <tr class="{{ $isWeekend ? 'bg-gray-50/50 text-gray-400' : 'hover:bg-gray-50/80' }} transition">
                            <!-- Date -->
                            <td class="px-4 py-3 font-bold text-gray-900 font-sans">
                                {{ $d['date']->format('d/m/Y') }}
                            </td>

                            <!-- Day -->
                            <td class="px-4 py-3 font-sans">
                                <span class="rounded-md px-2 py-0.5 text-[11px] font-bold {{ $isWeekend ? 'bg-gray-200 text-gray-600' : 'bg-indigo-50 text-indigo-700' }}">
                                    {{ $d['day_short'] }}
                                </span>
                            </td>

                            <!-- Scheduled -->
                            <td class="px-4 py-3 font-sans">
                                @if($d['schedules']->count() > 0)
                                    @foreach($d['schedules'] as $s)
                                        <div class="text-[11px] text-gray-800 font-semibold">{{ $s->formatted_schedule }} ({{ $s->unit->name }})</div>
                                    @endforeach
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>

                            <!-- Punches -->
                            <td class="px-3 py-3 text-center">
                                @if($d['punches']['entry_1'])
                                    <span class="font-bold text-emerald-700">{{ $d['punches']['entry_1']->recorded_at->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-center">
                                @if($d['punches']['exit_1'])
                                    <span class="font-bold text-amber-700">{{ $d['punches']['exit_1']->recorded_at->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-center">
                                @if($d['punches']['entry_2'])
                                    <span class="font-bold text-emerald-700">{{ $d['punches']['entry_2']->recorded_at->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <td class="px-3 py-3 text-center">
                                @if($d['punches']['exit_2'])
                                    <span class="font-bold text-rose-700">{{ $d['punches']['exit_2']->recorded_at->format('H:i') }}</span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>

                            <!-- Worked Hours -->
                            <td class="px-4 py-3 text-center font-bold font-sans {{ $d['worked_minutes'] > 0 ? 'text-gray-900' : 'text-gray-400' }}">
                                @if($d['worked_minutes'] > 0)
                                    {{ sprintf('%02dh %02dmin', floor($d['worked_minutes'] / 60), $d['worked_minutes'] % 60) }}
                                @else
                                    -
                                @endif
                            </td>

                            <!-- Occurrences & Certificates -->
                            <td class="px-4 py-3 font-sans">
                                @if($d['medical_cert'])
                                    <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 border border-blue-200 px-2 py-0.5 text-[11px] font-bold text-blue-700">
                                        🏥 Atestado Médico #{{ $d['medical_cert']->id }} (Justificado)
                                    </span>
                                @elseif($d['legal_leave'])
                                    <span class="inline-flex items-center gap-1 rounded-md bg-purple-50 border border-purple-200 px-2 py-0.5 text-[11px] font-bold text-purple-700">
                                        ⚖️ Folga Prevista em Lei (Aprovada)
                                    </span>
                                @elseif($d['delays'] > 0)
                                    <span class="rounded-md bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 text-[10.5px] font-semibold">
                                        ⏰ Atraso: +{{ $d['delays'] }}m
                                    </span>
                                @elseif(!$hasPunches && $d['expected_minutes'] > 0)
                                    <span class="rounded-md bg-rose-50 text-rose-700 border border-rose-200 px-2 py-0.5 text-[10.5px] font-bold">
                                        Falta
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">Normal</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
