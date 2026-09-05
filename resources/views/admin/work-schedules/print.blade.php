@extends('layouts.operational')

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 8mm 8mm 8mm 8mm;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 9.5pt !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .no-print, header, nav, footer, .sidebar, #sidebar {
            display: none !important;
        }
        .print-page-break {
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .print-shadow-none {
            box-shadow: none !important;
        }
        .print-container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
    }
</style>

<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10 print:bg-white print:p-0">
    <div class="max-w-7xl mx-auto space-y-6 print-container">

        <!-- Top Header & Actions (Hidden on Print) -->
        <div class="no-print flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Painel</a>
                    <span>/</span>
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-gray-800 font-semibold">Grade para Impressão & Publicação</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-teal-600 text-white flex items-center justify-center text-sm shadow-md shadow-teal-200">
                        🖨️
                    </span>
                    <span>Grade Horária Escolar (Impressão & Publicação)</span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5">
                    Visualize e imprima a grade por unidade, curso ou docente, formatada para publicação aos alunos e conferência oficial.
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
            <form method="GET" action="{{ route('admin.work-schedules.print') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
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
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Filtrar por Curso (Alunos)</label>
                    <select name="course_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white font-medium" onchange="this.form.submit()">
                        <option value="">-- Todos os Cursos --</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ $selectedCourseId == $c->id ? 'selected' : '' }}>
                                {{ $c->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Filtrar por Docente</label>
                    <select name="teacher_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" onchange="this.form.submit()">
                        <option value="">-- Todos os Professores --</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ $selectedTeacherId == $u->id ? 'selected' : '' }}>
                                {{ $u->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[11px] font-bold text-gray-700 uppercase mb-1">Turno</label>
                    <select name="shift_name" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-teal-500 bg-white" onchange="this.form.submit()">
                        <option value="">Todos os Turnos</option>
                        <option value="Manhã" {{ $selectedShift === 'Manhã' ? 'selected' : '' }}>🌅 Manhã</option>
                        <option value="Tarde" {{ $selectedShift === 'Tarde' ? 'selected' : '' }}>☀️ Tarde</option>
                        <option value="Noite" {{ $selectedShift === 'Noite' ? 'selected' : '' }}>🌙 Noite</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-bold py-2 text-xs transition cursor-pointer">
                        Filtrar
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
        <div class="bg-white rounded-3xl border border-gray-200 p-6 sm:p-8 shadow-xs space-y-6 print-shadow-none print:p-0 print:border-0">

            <!-- Cabeçalho Oficial da Instituição / Grade Escolar -->
            <div class="border-b-2 border-gray-800 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-900 text-white flex items-center justify-center font-black text-xl shadow-sm flex-shrink-0">
                        @if(file_exists(public_path('imagens/logo/etec.png')))
                            <img src="{{ asset('imagens/logo/etec.png') }}" alt="ETEC" class="h-8 w-auto object-contain">
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        @endif
                    </div>
                    <div>
                        <div class="text-[10.5px] font-extrabold text-indigo-900 uppercase tracking-wider">
                            CENTRO PAULA SOUZA • GOVERNO DO ESTADO DE SÃO PAULO
                        </div>
                        <h2 class="text-base sm:text-lg font-black uppercase tracking-tight text-gray-900">
                            ETEC SEBASTIANA AUGUSTA DE MORAES
                        </h2>
                        <div class="text-xs font-semibold text-gray-700 flex items-center gap-2 flex-wrap mt-0.5">
                            @if($selectedUnit)
                                <span class="inline-flex items-center font-bold text-gray-800 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded">
                                    {{ $selectedUnit->name }} {{ ($selectedUnit->city && !str_contains(strtoupper($selectedUnit->name), strtoupper($selectedUnit->city))) ? ' (' . $selectedUnit->city . ')' : '' }}
                                </span>
                            @else
                                <span class="inline-flex items-center text-gray-600 bg-gray-100 px-2 py-0.5 rounded font-medium">
                                    Todas as Unidades / Sedes
                                </span>
                            @endif
                            @if($selectedCourse)
                                <span class="rounded bg-indigo-100 text-indigo-900 border border-indigo-200 px-2 py-0.5 font-bold">
                                    🎓 {{ $selectedCourse->title }}
                                </span>
                            @endif
                            @if($selectedTeacher)
                                <span class="rounded bg-emerald-100 text-emerald-900 border border-emerald-200 px-2 py-0.5 font-bold">
                                    👨‍🏫 Docente: {{ $selectedTeacher->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="text-left sm:text-right text-xs">
                    <div class="font-extrabold text-gray-900 uppercase">
                        @if($selectedCourse)
                            GRADE CURRICULAR DA TURMA (ALUNOS)
                        @elseif($selectedTeacher)
                            GRADE INDIVIDUAL DO DOCENTE
                        @else
                            HORÁRIO GERAL DE TRABALHO & AULAS
                        @endif
                    </div>
                    <div class="text-gray-500 font-medium">
                        Ano Letivo {{ date('Y') }} • Emissão: {{ date('d/m/Y H:i') }}
                    </div>
                    @if($selectedShift)
                        <div class="text-[11px] font-bold text-indigo-600 mt-0.5">
                            Turno: {{ $selectedShift }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Matriz Semanal em Tabela / Colunas (Segunda a Sexta ou Sábado se houver aulas) -->
            <div class="w-full overflow-x-auto print:overflow-visible">
                <div class="grid gap-3 items-start min-w-[760px] print:min-w-0"
                     style="grid-template-columns: repeat({{ count($activeDays) }}, minmax(0, 1fr));">
                    @foreach($activeDays as $day)
                        @php
                            $dayConf = $dayColorConfigs[$day] ?? [
                                'name' => 'Dia',
                                'short' => 'DIA',
                                'hex' => '#475569',
                                'border_hex' => '#94a3b8',
                                'light_bg' => '#f8fafc',
                            ];
                            $daySlots = $schedulesByDay->get($day, collect())->sortBy('start_time');
                            // Agrupa os horários do dia pelo mesmo intervalo de início e fim
                            $groupedTimeSlots = $daySlots->groupBy(function($item) {
                                return substr($item->start_time, 0, 5) . '-' . substr($item->end_time, 0, 5);
                            });
                        @endphp

                        <!-- Coluna do Dia (Card Completo com Cabeçalho e Lista de Aulas Abaixo) -->
                        <div class="rounded-2xl border-2 overflow-hidden flex flex-col bg-white shadow-2xs print-page-break"
                             style="border-color: {{ $dayConf['border_hex'] }};">

                            <!-- Cabeçalho do Dia com Cor Sólida Temática -->
                            <div class="px-3 py-2 text-white flex items-center justify-between"
                                 style="background-color: {{ $dayConf['hex'] }};">
                                <div class="flex items-center gap-1 font-extrabold text-xs tracking-wide">
                                    <span>{{ $dayConf['short'] }}</span>
                                    <span class="opacity-90 font-medium text-[11px]">• {{ $dayConf['name'] }}</span>
                                </div>
                                <span class="text-[9.5px] font-bold px-1.5 py-0.5 rounded-full bg-white/20 text-white shadow-2xs">
                                    {{ $daySlots->count() }}
                                </span>
                            </div>

                            <!-- Lista de Aulas e Horários do Dia (Abaixo do cabeçalho) -->
                            <div class="p-2 space-y-2 flex-1" style="background-color: {{ $dayConf['light_bg'] }}30;">
                                @forelse($groupedTimeSlots as $timeKey => $slotsInTime)
                                    @php
                                        $firstSlot = $slotsInTime->first();
                                        $isMultiSlot = $slotsInTime->count() > 1;
                                    @endphp

                                    @if($isMultiSlot)
                                        {{-- ======================================================== --}}
                                        {{-- TURMAS DIVIDIDAS (A e B) NA MESMA LINHA EM COLUNAS       --}}
                                        {{-- ======================================================== --}}
                                        <div class="rounded-xl border bg-white p-2 text-left shadow-2xs transition print-page-break space-y-1.5"
                                             style="border-left-width: 4px; border-left-color: {{ $dayConf['hex'] }}; border-color: {{ $dayConf['border_hex'] }};">

                                            <!-- Barra Superior do Horário Unificado -->
                                            <div class="flex items-center justify-between gap-1 pb-1 border-b" style="border-color: {{ $dayConf['border_hex'] }}40;">
                                                <span class="font-mono text-[10px] font-black px-1.5 py-0.5 rounded shadow-2xs"
                                                      style="background-color: {{ $dayConf['light_bg'] }}; color: {{ $dayConf['hex'] }}; border: 1px solid {{ $dayConf['border_hex'] }};">
                                                    {{ $firstSlot->formatted_start_time }} - {{ $firstSlot->formatted_end_time }}
                                                </span>
                                                <span class="text-[8.5px] font-extrabold uppercase px-1 py-0.5 rounded bg-amber-100 text-amber-900 border border-amber-300">
                                                    A / B
                                                </span>
                                            </div>

                                            <!-- Sub-colunas Lado a Lado para Turma A e Turma B (com Cores do Professor) -->
                                            <div class="grid grid-cols-2 gap-1">
                                                @foreach($slotsInTime as $sched)
                                                    @php
                                                        $tColor = $sched->teacher_color;
                                                        $isA = $sched->division === 'A' || str_contains(strtoupper($sched->subject_name ?? ''), '(A)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA A');
                                                        $isB = $sched->division === 'B' || str_contains(strtoupper($sched->subject_name ?? ''), '(B)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA B');
                                                    @endphp
                                                    <div class="rounded-lg p-1.5 border shadow-2xs space-y-1 flex flex-col justify-between transition"
                                                         style="background-color: {{ $tColor['bg'] }}; border-color: {{ $tColor['border'] }}; border-left-width: 3.5px; border-left-color: {{ $tColor['dot'] }};">
                                                        <div>
                                                            <!-- Tag Turma A / B / Geral -->
                                                            <div class="flex items-center justify-between gap-0.5 mb-1">
                                                                @if($isA)
                                                                    <span class="rounded bg-sky-600 text-white px-1.5 py-0.2 text-[8px] font-black uppercase shadow-2xs">
                                                                        (A)
                                                                    </span>
                                                                @elseif($isB)
                                                                    <span class="rounded bg-orange-600 text-white px-1.5 py-0.2 text-[8px] font-black uppercase shadow-2xs">
                                                                        (B)
                                                                    </span>
                                                                @else
                                                                    <span class="rounded text-white px-1.5 py-0.2 text-[8px] font-black uppercase shadow-2xs"
                                                                          style="background-color: {{ $tColor['dot'] }};">
                                                                        Geral
                                                                    </span>
                                                                @endif

                                                                @if($sched->classroom)
                                                                    <span class="text-[7.5px] font-bold text-gray-700 bg-white/90 px-1 py-0.2 rounded border truncate max-w-[45px] shadow-2xs"
                                                                          style="border-color: {{ $tColor['border'] }};" title="{{ $sched->classroom }}">
                                                                        {{ $sched->classroom }}
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <!-- Professor com Cor e Fonte Legível -->
                                                            <div class="flex items-start gap-1">
                                                                <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 mt-0.5 shadow-2xs" style="background-color: {{ $tColor['dot'] }};"></span>
                                                                <span class="font-bold text-[8.5px] leading-tight text-gray-900 break-words" title="{{ $sched->user->name }}">
                                                                    {{ $sched->user->name }}
                                                                </span>
                                                            </div>

                                                            <!-- Nome da Disciplina ou Atividade -->
                                                            @if($sched->isCoordinationSchedule())
                                                                <div class="text-[8.5px] font-bold text-purple-900 leading-tight mt-0.5">
                                                                    📋 Coordenação Pedagógica
                                                                </div>
                                                            @elseif($sched->isAdministrativeSchedule())
                                                                <div class="text-[8.5px] font-bold text-slate-900 leading-tight mt-0.5">
                                                                    🏢 Expediente Administrativo
                                                                </div>
                                                            @else
                                                                <div class="font-black text-[9px] text-gray-900 leading-tight break-words mt-0.5">
                                                                    {{ $sched->subject_name ?: ($sched->shift_name ?: 'Aula') }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if($sched->class_name)
                                                            <div class="text-[8px] font-semibold text-gray-700 truncate pt-0.5 border-t" style="border-color: {{ $tColor['border'] }};">
                                                                {{ $sched->class_name }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Intervalo se houver -->
                                            @if($firstSlot->break_start_time && $firstSlot->break_end_time)
                                                <div class="pt-0.5 border-t border-gray-100 text-[8.5px] text-amber-700 font-medium">
                                                    Int: {{ substr($firstSlot->break_start_time, 0, 5) }} às {{ substr($firstSlot->break_end_time, 0, 5) }}
                                                </div>
                                            @endif

                                        </div>

                                    @else
                                        {{-- ======================================================== --}}
                                        {{-- HORÁRIO INDIVIDUAL / TURMA COMPLETA                      --}}
                                        {{-- ======================================================== --}}
                                        @php
                                            $sched = $slotsInTime->first();
                                            $tColor = $sched->teacher_color;
                                            $hasDivA = $sched->division === 'A' || str_contains(strtoupper($sched->subject_name ?? ''), '(A)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA A');
                                            $hasDivB = $sched->division === 'B' || str_contains(strtoupper($sched->subject_name ?? ''), '(B)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA B');
                                        @endphp

                                        <div class="rounded-xl p-2 border text-left shadow-2xs transition print-page-break space-y-1.5"
                                             style="background-color: {{ $tColor['bg'] }}; border-color: {{ $tColor['border'] }}; border-left-width: 4px; border-left-color: {{ $tColor['dot'] }};">

                                            <!-- Linha 1: Horário e Turno -->
                                            <div class="flex items-center justify-between gap-1 pb-1 border-b" style="border-color: {{ $tColor['border'] }};">
                                                <span class="font-mono text-[10px] font-black px-1.5 py-0.5 rounded shadow-2xs bg-white text-gray-900 border"
                                                      style="border-color: {{ $tColor['border'] }};">
                                                    {{ $sched->formatted_start_time }} - {{ $sched->formatted_end_time }}
                                                </span>
                                                @if($sched->shift_name)
                                                    <span class="text-[9px] font-semibold text-gray-600 truncate max-w-[80px]">
                                                        {{ $sched->shift_name }}
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Linha 2: Professor com Cor Exclusiva (Fonte legível e break-words) -->
                                            <div class="flex items-start gap-1.5">
                                                <span class="w-2 h-2 rounded-full flex-shrink-0 mt-0.5 shadow-2xs" style="background-color: {{ $tColor['dot'] }};" title="{{ $tColor['name'] }}"></span>
                                                <span class="font-bold text-[9.5px] leading-tight text-gray-900 break-words" title="{{ $sched->user->name }}">
                                                    {{ $sched->user->name }}
                                                </span>
                                            </div>

                                            <!-- Linha 3: Curso (se atribuído e quando não há curso filtrado) -->
                                            @if(($sched->course_name || $sched->course) && empty($selectedCourseId))
                                                <div>
                                                    <span class="inline-flex items-center gap-1 rounded bg-white/90 border px-1.5 py-0.2 text-[8.5px] font-bold text-gray-900 leading-tight break-words shadow-2xs"
                                                          style="border-color: {{ $tColor['border'] }};">
                                                        🎓 {{ $sched->course_name ?? $sched->course->title }}
                                                    </span>
                                                </div>
                                            @endif

                                            <!-- Linha 4: Disciplina ou Atividade -->
                                            @if($sched->isCoordinationSchedule())
                                                <div class="rounded-lg bg-white/90 border px-1.5 py-0.5 text-purple-900 text-[9.5px] font-bold shadow-2xs"
                                                     style="border-color: {{ $tColor['border'] }};">
                                                    📋 Coordenação Pedagógica
                                                </div>
                                            @elseif($sched->isAdministrativeSchedule())
                                                <div class="rounded-lg bg-white/90 border px-1.5 py-0.5 text-slate-900 text-[9.5px] font-bold shadow-2xs"
                                                     style="border-color: {{ $tColor['border'] }};">
                                                    🏢 Expediente Administrativo
                                                </div>
                                            @else
                                                {{-- Aula com Disciplina & Turma --}}
                                                <div class="space-y-1">
                                                    @if($sched->subject_name)
                                                        <div class="font-black text-[10px] text-gray-900 leading-tight break-words">
                                                            {{ $sched->subject_name }}
                                                        </div>
                                                    @endif

                                                    <div class="flex flex-wrap items-center gap-1 text-[9px]">
                                                        @if($hasDivA)
                                                            <span class="rounded bg-sky-100 text-sky-800 border border-sky-300 px-1 py-0.2 font-extrabold shadow-2xs">
                                                                Turma (A)
                                                            </span>
                                                        @elseif($hasDivB)
                                                            <span class="rounded bg-orange-100 text-orange-800 border border-orange-300 px-1 py-0.2 font-extrabold shadow-2xs">
                                                                Turma (B)
                                                            </span>
                                                        @endif

                                                        @if($sched->class_name)
                                                            <span class="rounded bg-white/90 border px-1.5 py-0.2 font-semibold text-gray-800 shadow-2xs"
                                                                  style="border-color: {{ $tColor['border'] }};">
                                                                {{ $sched->class_name }}
                                                            </span>
                                                        @endif

                                                        @if($sched->classroom)
                                                            <span class="rounded bg-white/90 border px-1 py-0.2 font-medium text-gray-700 shadow-2xs"
                                                                  style="border-color: {{ $tColor['border'] }};">
                                                                {{ $sched->classroom }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Linha 5: Intervalo se houver -->
                                            @if($sched->break_start_time && $sched->break_end_time)
                                                <div class="pt-1 border-t text-[8.5px] text-amber-800 font-medium" style="border-color: {{ $tColor['border'] }};">
                                                    Int: {{ substr($sched->break_start_time, 0, 5) }} às {{ substr($sched->break_end_time, 0, 5) }}
                                                </div>
                                            @endif

                                        </div>
                                    @endif
                                @empty
                                    <div class="py-6 text-center text-xs text-gray-400 italic">
                                        Sem horários
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- LEGENDA OFICIAL DA GRADE (PROFESSORES & DIAS) -->
            <div class="border-t-2 border-gray-200 pt-5 space-y-4 print-page-break">
                <div>
                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-gray-700 mb-2 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        <span>Legenda de Cores dos Professores & Dias da Semana</span>
                    </h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2">
                        @foreach($teachersInSchedule as $teacher)
                            @php
                                $tColor = \App\Models\WorkSchedule::getTeacherColorForUser($teacher->id, $teacher->name);
                            @endphp
                            <div class="rounded-xl border p-2 text-xs flex items-center gap-2 shadow-2xs"
                                 style="background-color: {{ $tColor['bg'] }}; border-color: {{ $tColor['border'] }}; color: {{ $tColor['text'] }};">
                                <span class="w-3 h-3 rounded-full flex-shrink-0 shadow-2xs" style="background-color: {{ $tColor['dot'] }};"></span>
                                <div class="truncate font-semibold" title="{{ $teacher->name }}">
                                    {{ $teacher->name }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($coursesInSchedule->isNotEmpty())
                    <div class="pt-2 border-t border-gray-100">
                        <div class="text-[11px] font-bold text-gray-600 uppercase mb-1.5">Cursos Vinculados nesta Grade:</div>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($coursesInSchedule as $crs)
                                <span class="rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-1 text-[11px] font-semibold text-indigo-900">
                                    🎓 {{ $crs->title }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Rodapé com Assinaturas Oficiais para Impressão -->
            <div class="pt-8 border-t border-gray-200 grid grid-cols-1 sm:grid-cols-3 gap-6 text-center text-xs text-gray-600">
                <div class="border-t border-gray-400 pt-1.5">
                    <div class="font-bold text-gray-900">Coordenação Pedagógica</div>
                    <div class="text-[10px]">Visto / Data</div>
                </div>
                <div class="border-t border-gray-400 pt-1.5">
                    <div class="font-bold text-gray-900">Diretoria de Serviços Acadêmicos</div>
                    <div class="text-[10px]">Secretaria Acadêmica</div>
                </div>
                <div class="border-t border-gray-400 pt-1.5">
                    <div class="font-bold text-gray-900">Diretoria da Unidade Escolar</div>
                    <div class="text-[10px]">Homologação</div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
