@extends('layouts.operational')

@section('content')
@php
    $statusColors = [
        'pre_alocada' => '#f2994a',
        'aprovada' => '#2f80ed',
        'em_execucao' => '#27ae60',
        'aguardando_conferencia' => '#8b5cf6',
        'conferida' => '#06b6d4',
        'concluida' => '#56ccf2',
    ];
@endphp

<style>
    /* FullCalendar Modern Theme Overrides */
    .fc {
        font-family: inherit;
        --fc-border-color: rgba(226, 232, 240, 0.8);
        --fc-today-bg-color: rgba(239, 246, 255, 0.6);
        --fc-page-bg-color: transparent;
        --fc-neutral-bg-color: rgba(248, 250, 252, 0.7);
        --fc-list-event-hover-bg-color: #f1f5f9;
    }

    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 0.75rem;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .fc .fc-toolbar-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.02em;
    }

    .fc .fc-button-primary {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 700;
        font-size: 0.75rem;
        border-radius: 0.75rem;
        padding: 0.4rem 0.75rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.15s ease;
        text-transform: capitalize;
    }

    .fc .fc-button-primary:hover {
        background-color: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background-color: #2563eb;
        border-color: #2563eb;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    }

    .fc .fc-button-group {
        border-radius: 0.75rem;
        overflow: hidden;
        gap: 2px;
    }

    .fc .fc-timegrid-slot {
        height: 2.75rem !important;
        border-bottom: 1px dashed rgba(226, 232, 240, 0.8) !important;
    }

    .fc .fc-timegrid-slot-label-cushion {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
    }

    .fc .fc-col-header-cell {
        background: #f8fafc;
        padding: 0.6rem 0;
        border-bottom: 2px solid #cbd5e1 !important;
    }

    .fc .fc-col-header-cell-cushion {
        font-size: 0.75rem;
        font-weight: 800;
        color: #334155;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    /* Custom Event Card in Calendar */
    .fc-event {
        border-radius: 0.65rem !important;
        border: none !important;
        box-shadow: 0 2px 4px rgba(15, 23, 42, 0.08), 0 1px 2px rgba(15, 23, 42, 0.04) !important;
        cursor: pointer;
        padding: 0.25rem 0.4rem !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    .fc-event:hover {
        transform: translateY(-2px) scale(1.01);
        box-shadow: 0 6px 12px -2px rgba(15, 23, 42, 0.15) !important;
        z-index: 20 !important;
    }

    .fc-event-main {
        padding: 0 !important;
    }

    .calendar-event-pill {
        display: flex;
        flex-direction: column;
        gap: 1px;
        height: 100%;
        color: #ffffff;
    }

    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-5">
        <!-- Floating Action Button (Mobile Only) -->
        <a href="{{ route('lab.reservations.create') }}" class="lg:hidden fixed bottom-6 right-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-blue-600 text-white shadow-xl transition active:scale-95 hover:bg-blue-500 focus:outline-none" title="Nova Reserva">
            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
        </a>

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Mapa de Laboratórios</span>
                    <span class="rounded-xl bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 normal-case tracking-normal">Grade Semanal</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">Visualização dinâmica de grade de horários, ocupação e turnos dos ambientes didáticos</p>
            </div>

            <div class="hidden sm:flex flex-wrap items-center gap-3">
                <a href="{{ route('lab.reservations.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    <span>Nova Reserva</span>
                </a>
                <a href="{{ route('lab.reservations.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    <span>Quadro de Reservas</span>
                </a>
                <a href="{{ route('lab.reservations.history') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-teal-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Histórico Concluídas</span>
                </a>
            </div>
        </div>

        <!-- Interactive Glassmorphic Filter & Control Bar -->
        <div class="mb-4 rounded-2xl border border-gray-300 bg-white/70 p-3 shadow-sm backdrop-blur-md space-y-3">
            <!-- Row 1: Space Filter Pills (Horizontal Scroll) -->
            <div class="flex items-center justify-between gap-2">
                <div class="flex items-center gap-1.5 text-xs font-bold text-gray-700 uppercase flex-shrink-0">
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span>Filtrar Ambiente:</span>
                </div>

                <!-- Fast Room Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar flex-1 pb-1">
                    <button
                        type="button"
                        class="space-filter-pill flex-shrink-0 rounded-xl px-3 py-1.5 text-xs font-bold transition shadow-2xs border bg-blue-600 text-white border-blue-600"
                        data-space-id=""
                    >
                        Todos os Ambientes
                    </button>
                    @foreach($spaces as $space)
                        <button
                            type="button"
                            class="space-filter-pill flex-shrink-0 rounded-xl px-3 py-1.5 text-xs font-bold transition shadow-2xs border bg-white/90 text-gray-700 border-gray-300 hover:bg-gray-100"
                            data-space-id="{{ $space->id }}"
                        >
                            {{ $space->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Row 2: Turnos rápidos, Status Legend & Docente Filter -->
            <div class="flex flex-wrap items-center justify-between gap-2.5 pt-2.5 border-t border-gray-200/70">
                <!-- Turnos Rápido (Shift Filter) -->
                <div class="flex items-center gap-1.5">
                    <span class="text-[10.5px] font-bold text-gray-500 uppercase mr-1">Turno:</span>
                    <button type="button" class="shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-gray-900 text-white transition" data-start="07:00:00" data-end="23:00:00">
                        Dia Completo
                    </button>
                    <button type="button" class="shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition" data-start="07:00:00" data-end="13:00:00">
                        Manhã (07h-13h)
                    </button>
                    <button type="button" class="shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition" data-start="12:30:00" data-end="18:30:00">
                        Tarde (13h-18h)
                    </button>
                    <button type="button" class="shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition" data-start="18:00:00" data-end="23:00:00">
                        Noite (18h-23h)
                    </button>
                </div>

                <!-- Status Legend -->
                <div class="hidden lg:flex items-center gap-2">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Legenda:</span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700">
                        <span class="h-2 w-2 rounded-full bg-[#f2994a]"></span> Solicitada
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700">
                        <span class="h-2 w-2 rounded-full bg-[#2f80ed]"></span> Aprovada
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700">
                        <span class="h-2 w-2 rounded-full bg-[#27ae60]"></span> Em Aula
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700">
                        <span class="h-2 w-2 rounded-full bg-[#8b5cf6]"></span> Conferência
                    </span>
                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-gray-700">
                        <span class="h-2 w-2 rounded-full bg-[#56ccf2]"></span> Concluída
                    </span>
                </div>
            </div>
        </div>

        <!-- Main Fluid Calendar Container -->
        <div class="rounded-2xl border border-gray-300 bg-white/90 p-4 sm:p-5 shadow-sm backdrop-blur-sm">
            <div id='calendar' style="min-height: 700px;"></div>
        </div>
    </div>
</div>

<!-- Modal de Detalhes Rápidos do Evento -->
<div id="modalEventoCalendar" class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs hidden flex items-center justify-center z-50 p-4 transition-all">
    <div class="bg-white rounded-2xl p-6 w-full max-w-lg shadow-2xl border border-gray-100 transform transition-all scale-100">
        <!-- Header -->
        <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
            <div class="flex items-center gap-2">
                <span id="modalEventColorDot" class="h-3.5 w-3.5 rounded-full inline-block"></span>
                <h3 class="font-semibold text-base text-gray-800" id="modalEventSpaceTitle">Laboratório</h3>
                <span id="modalEventStatusBadge" class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wide"></span>
            </div>
            <button onclick="fecharModalEvento()" class="text-gray-400 hover:text-gray-600 text-xl font-bold">&times;</button>
        </div>

        <!-- Body Details -->
        <div class="space-y-3.5 mb-6 text-xs">
            <div class="grid grid-cols-2 gap-2.5">
                <div class="rounded-xl bg-gray-50 p-2.5 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Docente</span>
                    <p id="modalEventTeacherName" class="font-bold text-gray-900 mt-0.5"></p>
                </div>
                <div class="rounded-xl bg-gray-50 p-2.5 border border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase block">Data & Horário</span>
                    <p id="modalEventDateTime" class="font-bold text-blue-600 mt-0.5"></p>
                </div>
            </div>

            <div class="rounded-xl bg-gray-50 p-3 border border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1">Plano de Aula / Descrição:</span>
                <p id="modalEventLessonPlan" class="text-gray-700 whitespace-pre-line leading-relaxed"></p>
            </div>

            <div class="rounded-xl bg-gray-50 p-3 border border-gray-100">
                <span class="text-[10px] font-bold text-gray-400 uppercase block mb-1.5">Materiais Requisitados:</span>
                <div id="modalEventMaterialsList" class="flex flex-wrap gap-1.5"></div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
            <a id="modalEventPdfBtn" href="#" target="_blank" class="inline-flex items-center gap-1 rounded-xl bg-gray-800 px-3.5 py-2 text-xs font-bold text-white shadow-2xs hover:bg-gray-700 transition">
                📄 Imprimir PDF
            </a>
            <a id="modalEventShowBtn" href="#" class="inline-flex items-center gap-1 rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white shadow-2xs hover:bg-blue-500 transition">
                Ver Detalhes Completos
            </a>
            <button type="button" onclick="fecharModalEvento()" class="rounded-xl bg-gray-100 px-3.5 py-2 text-xs font-bold text-gray-600 hover:bg-gray-200 transition">
                Fechar
            </button>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/pt-br.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        let currentSpaceId = '';
        const spacePills = document.querySelectorAll('.space-filter-pill');
        const shiftPills = document.querySelectorAll('.shift-filter-pill');

        const isMobile = window.innerWidth < 768;

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: isMobile ? 'timeGridDay' : 'timeGridWeek',
            locale: 'pt-br',
            buttonText: {
                today: 'Hoje',
                month: 'Mês',
                week: 'Semana',
                day: 'Dia',
                list: 'Lista'
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '23:00:00',
            slotDuration: '00:30:00',
            slotLabelInterval: '01:00',
            allDaySlot: false,
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek,timeGridDay,dayGridMonth,listWeek'
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                const url = `{{ route('lab.api.calendar') }}?space_id=${currentSpaceId}`;
                fetch(url)
                    .then(res => res.json())
                    .then(data => successCallback(data))
                    .catch(err => failureCallback(err));
            },
            eventContent: function(arg) {
                const props = arg.event.extendedProps;
                const timeText = arg.timeText;
                const spaceName = props.spaceName || arg.event.title;
                const teacherName = props.teacherName || '';

                const customEl = document.createElement('div');
                customEl.className = 'calendar-event-pill p-1';
                customEl.innerHTML = `
                    <div class="flex items-center justify-between gap-1 overflow-hidden leading-tight">
                        <span class="font-extrabold text-[10.5px] truncate">${spaceName}</span>
                        <span class="text-[9px] font-bold opacity-90">${timeText}</span>
                    </div>
                    <div class="text-[9.5px] font-medium opacity-95 truncate mt-0.5">
                        👤 ${teacherName}
                    </div>
                `;
                return { domNodes: [customEl] };
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                abrirModalEvento(info.event);
            },
            dayHeaderFormat: { weekday: 'short', day: 'numeric', month: 'numeric' },
        });

        calendar.render();

        // Space Filter Pills handler
        spacePills.forEach(pill => {
            pill.addEventListener('click', function() {
                currentSpaceId = this.dataset.spaceId || '';
                spacePills.forEach(p => {
                    p.className = 'space-filter-pill flex-shrink-0 rounded-xl px-3 py-1.5 text-xs font-bold transition shadow-2xs border bg-white/90 text-gray-700 border-gray-300 hover:bg-gray-100';
                });
                this.className = 'space-filter-pill flex-shrink-0 rounded-xl px-3 py-1.5 text-xs font-bold transition shadow-2xs border bg-blue-600 text-white border-blue-600';
                calendar.refetchEvents();
            });
        });

        // Shift Filter Pills handler
        shiftPills.forEach(pill => {
            pill.addEventListener('click', function() {
                const start = this.dataset.start;
                const end = this.dataset.end;

                shiftPills.forEach(p => {
                    p.className = 'shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-white text-gray-700 hover:bg-gray-100 transition';
                });
                this.className = 'shift-filter-pill rounded-lg px-2.5 py-1 text-[11px] font-bold border border-gray-300 bg-gray-900 text-white transition';

                calendar.setOption('slotMinTime', start);
                calendar.setOption('slotMaxTime', end);
            });
        });
    });

    function abrirModalEvento(event) {
        const props = event.extendedProps;
        document.getElementById('modalEventColorDot').style.backgroundColor = props.statusColor || event.backgroundColor || '#3b82f6';
        document.getElementById('modalEventSpaceTitle').innerText = props.spaceName || event.title || 'Ambiente';

        const statusBadge = document.getElementById('modalEventStatusBadge');
        statusBadge.innerText = props.statusLabel || '';
        statusBadge.style.backgroundColor = props.statusColor ? props.statusColor + '20' : '#dbeafe';
        statusBadge.style.color = props.statusColor || '#1e40af';

        document.getElementById('modalEventTeacherName').innerText = props.teacherName || '-';
        document.getElementById('modalEventDateTime').innerText = props.dateFormatted ? `${props.dateFormatted} às ${props.timeFormatted}` : event.startStr;
        document.getElementById('modalEventLessonPlan').innerText = props.lessonPlan || props.description || 'Sem descrição cadastrada.';

        const materialsList = document.getElementById('modalEventMaterialsList');
        materialsList.innerHTML = '';
        if (props.materials && props.materials.length > 0) {
            props.materials.forEach(m => {
                materialsList.innerHTML += `
                    <span class="inline-flex items-center rounded-lg bg-blue-50 px-2 py-1 text-[10.5px] font-bold text-blue-700 border border-blue-100">
                        ${m.name} (x${m.qty || m.quantity || 1})
                    </span>`;
            });
        } else {
            materialsList.innerHTML = '<span class="text-xs text-gray-400 italic">Nenhum material extra solicitado.</span>';
        }

        document.getElementById('modalEventPdfBtn').href = props.pdfUrl || '#';
        document.getElementById('modalEventShowBtn').href = props.showUrl || event.url || '#';

        document.getElementById('modalEventoCalendar').classList.remove('hidden');
    }

    function fecharModalEvento() {
        document.getElementById('modalEventoCalendar').classList.add('hidden');
    }
</script>
@endsection

