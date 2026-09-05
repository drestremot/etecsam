@extends('layouts.operational')

@section('content')
<style>
@media print {
    @page {
        size: landscape A4;
        margin: 8mm 6mm;
    }
    body {
        background-color: #ffffff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .no-print, header, nav, aside {
        display: none !important;
    }
    .print-container {
        padding: 0 !important;
        margin: 0 !important;
        max-width: 100% !important;
        background: #ffffff !important;
    }
    .print-page-break {
        page-break-inside: avoid;
    }
    .print-shadow-none {
        box-shadow: none !important;
    }
}
</style>

<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10 print-container">
    <div class="w-full max-w-[1900px] mx-auto space-y-5">

        <!-- Top Navigation & Action Bar (Hidden on Print) -->
        <div class="no-print flex flex-wrap items-center justify-between gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-gray-200 shadow-xs">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Impressão de Grade</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Grade de Horários por Unidade Escolar</span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5">
                    Visualização formatada para conferência em tela e impressão oficial em folha A4 paisagem
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white font-bold px-4 py-2.5 text-xs shadow-md shadow-teal-200 transition cursor-pointer">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimir Grade (A4 / PDF)</span>
                </button>

                <a href="{{ route('admin.work-schedules.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold px-4 py-2.5 text-xs shadow-xs transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Editar / Cadastrar</span>
                </a>

                <a href="{{ route('admin.work-schedules.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-white border border-gray-300 px-4 py-2.5 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        <!-- Filter Bar (Hidden on Print) -->
        <div class="no-print bg-white p-4 rounded-2xl border border-gray-200 shadow-xs">
            <form method="GET" action="{{ route('admin.work-schedules.print') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Unidade Escolar *</label>
                    <select name="unit_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" onchange="this.form.submit()">
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ ($selectedUnit && $selectedUnit->id == $un->id) ? 'selected' : '' }}>
                                {{ $un->name }} ({{ $un->city }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Filtrar Turno</label>
                    <select name="shift_name" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" onchange="this.form.submit()">
                        <option value="">Todos os Turnos</option>
                        <option value="Manhã" {{ $selectedShift === 'Manhã' ? 'selected' : '' }}>🌅 Manhã</option>
                        <option value="Tarde" {{ $selectedShift === 'Tarde' ? 'selected' : '' }}>☀️ Tarde</option>
                        <option value="Noite" {{ $selectedShift === 'Noite' ? 'selected' : '' }}>🌙 Noite</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Tipo de Horário</label>
                    <select name="schedule_type" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" onchange="this.form.submit()">
                        <option value="">Todos os Tipos</option>
                        <option value="class" {{ $selectedType === 'class' ? 'selected' : '' }}>👨‍🏫 Aulas (Docentes)</option>
                        <option value="coordination" {{ $selectedType === 'coordination' ? 'selected' : '' }}>📋 Coordenação</option>
                        <option value="administrative" {{ $selectedType === 'administrative' ? 'selected' : '' }}>🏢 Administrativo</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-bold py-2 text-xs transition">
                        Atualizar Visualização
                    </button>
                    <a href="{{ route('admin.work-schedules.print') }}" class="rounded-xl border border-gray-300 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 transition">
                        Limpar
                    </a>
                </div>
            </form>
        </div>

        {{-- ================================================================= --}}
        {{-- DOCUMENTO OFICIAL IMPRESSO / PAINEL DA GRADE                      --}}
        {{-- ================================================================= --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs print-shadow-none print-page-break space-y-6">

            <!-- Cabeçalho Oficial Institucional -->
            <div class="border-b-2 border-gray-900 pb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-extrabold text-base tracking-wider flex-shrink-0">
                        ETEC
                    </div>
                    <div>
                        <div class="text-[10px] uppercase font-bold text-gray-500 tracking-wider">Centro Paula Souza • Governo do Estado de SP</div>
                        <h2 class="text-lg sm:text-xl font-black text-gray-900 tracking-tight leading-snug">
                            {{ $selectedUnit ? $selectedUnit->name : 'Unidade Escolar' }}
                        </h2>
                        <div class="text-xs text-gray-600 font-medium">
                            {{ $selectedUnit ? $selectedUnit->city : '' }} • Grade Horária Semanal dos Docentes e Colaboradores
                        </div>
                    </div>
                </div>

                <div class="text-left sm:text-right text-[11px] text-gray-500 space-y-0.5">
                    <div><strong>Data de Emissão:</strong> {{ now()->format('d/m/Y H:i') }}</div>
                    <div><strong>Total de Horários:</strong> {{ $schedules->count() }} cadastrados</div>
                    @if($selectedShift)
                        <div><span class="rounded bg-teal-50 text-teal-800 font-bold px-1.5 py-0.2 border border-teal-200">Turno: {{ $selectedShift }}</span></div>
                    @endif
                </div>
            </div>

            @if($schedules->isEmpty())
                <div class="py-16 text-center text-gray-400 space-y-2">
                    <svg class="w-12 h-12 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <p class="text-sm font-semibold text-gray-600">Nenhum horário registrado para esta unidade com os filtros selecionados.</p>
                    <p class="text-xs text-gray-400">Utilize o construtor de grade para lançar as aulas e horários dos professores.</p>
                </div>
            @else

            <!-- MATRIZ SEMANAL DA GRADE (SEGUNDA A SÁBADO) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 items-start">
                @foreach($activeDays as $day)
                    @php
                        $dayConf = $dayColorConfigs[$day] ?? [
                            'name' => $daysList[$day] ?? 'Dia',
                            'short' => 'DIA',
                            'hex' => '#475569',
                            'light_bg' => '#f8fafc',
                            'border_hex' => '#cbd5e1',
                        ];
                        $daySlots = $schedulesByDay->get($day, collect())->sortBy('start_time');
                    @endphp

                    <div class="rounded-2xl border-2 transition overflow-hidden print-page-break"
                         style="border-color: {{ $dayConf['hex'] }}; background-color: {{ $dayConf['light_bg'] }};">

                        <!-- Cabeçalho do Dia com Cor Temática -->
                        <div class="px-3.5 py-2.5 text-white flex items-center justify-between"
                             style="background-color: {{ $dayConf['hex'] }};">
                            <div class="flex items-center gap-1.5 font-extrabold text-xs tracking-wide">
                                <span>{{ $dayConf['short'] }}</span>
                                <span class="opacity-85 font-medium">• {{ $dayConf['name'] }}</span>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20">
                                {{ $daySlots->count() }}
                            </span>
                        </div>

                        <!-- Lista de Aulas e Horários do Dia -->
                        <div class="p-2.5 space-y-2">
                            @forelse($daySlots as $sched)
                                @php
                                    $tColor = $sched->teacher_color;
                                    $hasDivA = str_contains(strtoupper($sched->subject_name ?? ''), '(A)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA A');
                                    $hasDivB = str_contains(strtoupper($sched->subject_name ?? ''), '(B)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA B');
                                @endphp

                                <div class="rounded-xl p-2.5 border text-left shadow-2xs transition print-page-break bg-white"
                                     style="border-left-width: 5px; border-left-color: {{ $dayConf['hex'] }}; border-color: {{ $dayConf['border_hex'] }};">

                                    <!-- Linha 1: Horário e Turno -->
                                    <div class="flex items-center justify-between gap-1 mb-1.5 pb-1 border-b" style="border-color: {{ $dayConf['border_hex'] }}50;">
                                        <span class="font-mono text-[11px] font-extrabold px-1.5 py-0.5 rounded shadow-2xs"
                                              style="background-color: {{ $dayConf['light_bg'] }}; color: {{ $dayConf['hex'] }}; border: 1px solid {{ $dayConf['border_hex'] }};">
                                            {{ $sched->formatted_start_time }} - {{ $sched->formatted_end_time }}
                                        </span>
                                        @if($sched->shift_name)
                                            <span class="text-[9.5px] font-semibold text-gray-500 truncate max-w-[90px]">
                                                {{ $sched->shift_name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Linha 2: Professor com Cor Exclusiva -->
                                    <div class="flex items-center gap-1.5 mb-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-2xs" style="background-color: {{ $tColor['dot'] }};" title="{{ $tColor['name'] }}"></span>
                                        <span class="font-bold text-xs truncate text-gray-900" title="{{ $sched->user->name }}">
                                            {{ $sched->user->name }}
                                        </span>
                                    </div>

                                    <!-- Linha 3: Disciplina ou Atividade -->
                                    @if($sched->isCoordinationSchedule())
                                        <div class="rounded-lg bg-purple-50 border border-purple-200 px-2 py-1 text-purple-700 text-[10.5px] font-bold">
                                            📋 Coordenação Pedagógica
                                        </div>
                                    @elseif($sched->isAdministrativeSchedule())
                                        <div class="rounded-lg bg-slate-100 border border-slate-200 px-2 py-1 text-slate-700 text-[10.5px] font-bold">
                                            🏢 Expediente Administrativo
                                        </div>
                                    @else
                                        {{-- Aula com Disciplina & Turma --}}
                                        <div class="space-y-1">
                                            @if($sched->subject_name)
                                                <div class="font-bold text-[11.5px] text-gray-900 leading-tight">
                                                    {{ $sched->subject_name }}
                                                </div>
                                            @endif

                                            <div class="flex flex-wrap items-center gap-1 text-[10px]">
                                                @if($hasDivA)
                                                    <span class="rounded bg-sky-100 text-sky-800 border border-sky-300 px-1.5 py-0.2 font-bold">
                                                        Turma (A)
                                                    </span>
                                                @elseif($hasDivB)
                                                    <span class="rounded bg-orange-100 text-orange-800 border border-orange-300 px-1.5 py-0.2 font-bold">
                                                        Turma (B)
                                                    </span>
                                                @endif

                                                @if($sched->class_name)
                                                    <span class="rounded bg-indigo-50 text-indigo-700 border border-indigo-200 px-1.5 py-0.2 font-semibold">
                                                        {{ $sched->class_name }}
                                                    </span>
                                                @endif

                                                @if($sched->classroom)
                                                    <span class="rounded bg-gray-100 text-gray-700 px-1 py-0.2 font-medium">
                                                        {{ $sched->classroom }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Linha 4: Intervalo se houver -->
                                    @if($sched->break_start_time && $sched->break_end_time)
                                        <div class="mt-1.5 pt-1 border-t border-gray-100 text-[9.5px] text-amber-700">
                                            Intervalo: {{ substr($sched->break_start_time, 0, 5) }} às {{ substr($sched->break_end_time, 0, 5) }}
                                        </div>
                                    @endif

                                </div>
                            @empty
                                <div class="py-6 text-center text-xs text-gray-400 italic">
                                    Sem horários
                                </div>
                            @endforelse
                        </div>

                    </div>
                @endforeach
            </div>

            <!-- LEGENDA OFICIAL DA GRADE (PROFESSORES & DIAS) -->
            <div class="border-t-2 border-gray-200 pt-5 space-y-4 print-page-break">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        <span>Legenda de Professores & Colaboradores da Unidade</span>
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($teachersInSchedule as $teacher)
                            @php
                                $color = $teacher->teacher_color;
                            @endphp
                            <div class="inline-flex items-center gap-1.5 rounded-lg border px-2.5 py-1 text-xs font-semibold"
                                 style="background-color: {{ $color['bg'] }}; border-color: {{ $color['border'] }}; color: {{ $color['text'] }};">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $color['dot'] }};"></span>
                                <span>{{ $teacher->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-[10.5px] text-gray-500 border-t border-gray-100 pt-3">
                    <div>
                        <strong>Divisões de Turma:</strong> Disciplinas com desdobramento prático/laboratório identificadas por <span class="font-bold text-sky-800">Turma (A)</span> e <span class="font-bold text-orange-800">Turma (B)</span>.
                    </div>
                    <div class="text-left sm:text-right">
                        <strong>Perfis Especiais:</strong> Coordenadores possuem horário de coordenação pedagógica (sem disciplina vinculada). Funcionários cumprem expediente administrativo.
                    </div>
                </div>
            </div>

            @endif

        </div>

    </div>
</div>
@endsection