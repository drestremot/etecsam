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
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-900 text-white flex items-center justify-center font-black text-xl shadow-sm">
                        SAM
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black uppercase tracking-tight text-gray-900">
                            ETEC SANTO ANTÔNIO DE MATÃO
                        </h2>
                        <div class="text-xs font-semibold text-gray-700 flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center gap-1">
                                🏢 {{ $selectedUnit ? $selectedUnit->name . ' (' . $selectedUnit->city . ')' : 'Todas as Unidades' }}
                            </span>
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
                        Ano Letivo 2026 • Emissão: {{ date('d/m/Y H:i') }}
                    </div>
                    @if($selectedShift)
                        <div class="text-[11px] font-bold text-indigo-600 mt-0.5">
                            Turno: {{ $selectedShift }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Matriz Semanal em Colunas (Segunda a Sábado com Cores Exclusivas por Dia) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3.5 items-start">
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
                    @endphp

                    <div class="rounded-2xl border-2 transition overflow-hidden print-page-break"
                         style="border-color: {{ $dayConf['hex'] }}; background-color: {{ $dayConf['light_bg'] }};">

                        <!-- Cabeçalho do Dia com Cor Sólida Temática -->
                        <div class="px-3.5 py-2.5 text-white flex items-center justify-between"
                             style="background-color: {{ $dayConf['hex'] }};">
                            <div class="flex items-center gap-1.5 font-extrabold text-xs tracking-wide">
                                <span>{{ $dayConf['short'] }}</span>
                                <span class="opacity-85 font-medium">• {{ $dayConf['name'] }}</span>
                            </div>
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-white/20 text-white shadow-2xs">
                                {{ $daySlots->count() }}
                            </span>
                        </div>

                        <!-- Lista de Aulas e Horários do Dia -->
                        <div class="p-2.5 space-y-2">
                            @forelse($daySlots as $sched)
                                @php
                                    $tColor = $sched->teacher_color;
                                    $hasDivA = $sched->division === 'A' || str_contains(strtoupper($sched->subject_name ?? ''), '(A)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA A');
                                    $hasDivB = $sched->division === 'B' || str_contains(strtoupper($sched->subject_name ?? ''), '(B)') || str_contains(strtoupper($sched->subject_name ?? ''), 'TURMA B');
                                @endphp

                                <div class="rounded-xl p-2.5 border text-left shadow-2xs transition print-page-break bg-white space-y-1.5"
                                     style="border-left-width: 5px; border-left-color: {{ $dayConf['hex'] }}; border-color: {{ $dayConf['border_hex'] }};">

                                    <!-- Linha 1: Horário e Turno -->
                                    <div class="flex items-center justify-between gap-1 pb-1 border-b" style="border-color: {{ $dayConf['border_hex'] }}50;">
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
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0 shadow-2xs" style="background-color: {{ $tColor['dot'] }};" title="{{ $tColor['name'] }}"></span>
                                        <span class="font-bold text-xs truncate text-gray-900" title="{{ $sched->user->name }}">
                                            {{ $sched->user->name }}
                                        </span>
                                    </div>

                                    <!-- Linha 3: Curso (se atribuído) -->
                                    @if($sched->course_name || $sched->course)
                                        <div>
                                            <span class="inline-flex items-center gap-1 rounded bg-indigo-50 border border-indigo-200 px-1.5 py-0.2 text-[9.5px] font-bold text-indigo-900 leading-tight">
                                                🎓 {{ $sched->course_name ?? $sched->course->title }}
                                            </span>
                                        </div>
                                    @endif

                                    <!-- Linha 4: Disciplina ou Atividade -->
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
                                                    <span class="rounded bg-sky-100 text-sky-800 border border-sky-300 px-1.5 py-0.2 font-extrabold">
                                                        Turma (A)
                                                    </span>
                                                @elseif($hasDivB)
                                                    <span class="rounded bg-orange-100 text-orange-800 border border-orange-300 px-1.5 py-0.2 font-extrabold">
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

                                    <!-- Linha 5: Intervalo se houver -->
                                    @if($sched->break_start_time && $sched->break_end_time)
                                        <div class="pt-1 border-t border-gray-100 text-[9.5px] text-amber-700 font-medium">
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