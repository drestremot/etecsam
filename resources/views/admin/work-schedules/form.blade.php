@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">

    @if($action === 'create')
    {{-- ========================================================================= --}}
    {{-- CONSTRUTOR INTERATIVO DE GRADE COMPLETA (MODO CRIAÇÃO)                   --}}
    {{-- ========================================================================= --}}
    <script>
        window.__WS_CONFIG__ = {
            users: {!! json_encode($users->map(fn($u) => ['id' => $u->id, 'name' => $u->name, 'role' => $u->role ?? 'Docente'])->values()) !!},
            units: {!! json_encode($units->map(fn($un) => ['id' => $un->id, 'name' => $un->name, 'city' => $un->city])->values()) !!},
            daysList: {!! json_encode($daysList) !!},
            oldUserId: '{{ old('user_id', request('user_id', $initialUserId ?? '')) }}',
            oldUnitId: '{{ old('unit_id', $units->first()?->id ?? '') }}',
            allUserSchedules: {!! json_encode($allUserSchedules ?? []) !!}
        };

        function scheduleBuilder(config) {
            return {
                users: config.users || [],
                units: config.units || [],
                daysList: config.daysList || {},
                dayShortNames: { 1: 'Seg', 2: 'Ter', 3: 'Qua', 4: 'Qui', 5: 'Sex', 6: 'Sáb', 0: 'Dom' },
                allUserSchedules: config.allUserSchedules || {},

                userId: config.oldUserId || (config.users[0]?.id || ''),
                defaultUnitId: config.oldUnitId || (config.units[0]?.id || ''),

                currentDays: [1],
                currentUnitId: config.oldUnitId || (config.units[0]?.id || ''),
                currentStartTime: '07:10',
                currentEndTime: '12:35',
                currentShiftName: '',
                currentBreakStart: '',
                currentBreakEnd: '',
                currentTolerance: 15,

                slots: [],
                counter: 1,

                init() {
                    if (this.userId) {
                        this.loadUserSchedules(this.userId);
                    }
                },

                get selectedUserName() {
                    const u = this.users.find(x => String(x.id) === String(this.userId));
                    return u ? u.name : '';
                },

                get uniqueDaysCount() {
                    const unique = new Set(this.slots.map(s => s.day_of_week));
                    return unique.size;
                },

                get totalHoursFormatted() {
                    let totalMinutes = 0;
                    this.slots.forEach(s => {
                        const [sh, sm] = s.start_time.split(':').map(Number);
                        const [eh, em] = s.end_time.split(':').map(Number);
                        const startM = sh * 60 + sm;
                        const endM = eh * 60 + em;
                        if (endM > startM) {
                            totalMinutes += (endM - startM);
                        }
                    });

                    const h = Math.floor(totalMinutes / 60);
                    const m = totalMinutes % 60;
                    if (h === 0 && m === 0) return '0h';
                    if (m === 0) return `${h}h semanais`;
                    return `${h}h ${m}min semanais`;
                },

                onUserChange() {
                    this.slots = [];
                    if (this.userId) {
                        this.loadUserSchedules(this.userId);
                    }
                },

                loadUserSchedules(userId) {
                    if (!userId) {
                        this.slots = [];
                        return;
                    }

                    // 1. Carregamento instantâneo a partir do dicionário em memória
                    if (this.allUserSchedules && this.allUserSchedules[userId] && this.allUserSchedules[userId].length > 0) {
                        const data = this.allUserSchedules[userId];
                        this.slots = data.map(item => ({
                            temp_id: 'slot_' + (this.counter++),
                            day_of_week: Number(item.day_of_week),
                            unit_id: Number(item.unit_id),
                            start_time: item.start_time ? item.start_time.substring(0, 5) : '07:10',
                            end_time: item.end_time ? item.end_time.substring(0, 5) : '12:35',
                            shift_name: item.shift_name || '',
                            break_start_time: item.break_start_time ? item.break_start_time.substring(0, 5) : null,
                            break_end_time: item.break_end_time ? item.break_end_time.substring(0, 5) : null,
                            tolerance_minutes: item.tolerance_minutes || 15
                        }));
                        return;
                    }

                    // 2. Requisição assíncrona caso não esteja precarregado
                    fetch(`/admin/work-schedules/user/${userId}`)
                        .then(r => r.json())
                        .then(data => {
                            if (Array.isArray(data) && data.length > 0) {
                                this.slots = data.map(item => ({
                                    temp_id: 'slot_' + (this.counter++),
                                    day_of_week: Number(item.day_of_week),
                                    unit_id: Number(item.unit_id),
                                    start_time: item.start_time ? item.start_time.substring(0, 5) : '07:10',
                                    end_time: item.end_time ? item.end_time.substring(0, 5) : '12:35',
                                    shift_name: item.shift_name || '',
                                    break_start_time: item.break_start_time ? item.break_start_time.substring(0, 5) : null,
                                    break_end_time: item.break_end_time ? item.break_end_time.substring(0, 5) : null,
                                    tolerance_minutes: item.tolerance_minutes || 15
                                }));
                            } else {
                                this.slots = [];
                            }
                        })
                        .catch(() => {
                            this.slots = [];
                        });
                },

                onDefaultUnitChange() {
                    this.currentUnitId = this.defaultUnitId;
                },

                selectWeekdays() {
                    this.currentDays = [1, 2, 3, 4, 5];
                },

                toggleDay(day) {
                    if (this.currentDays.includes(day)) {
                        this.currentDays = this.currentDays.filter(d => d !== day);
                    } else {
                        this.currentDays.push(day);
                    }
                },

                applyPreset(start, end, name) {
                    this.currentStartTime = start;
                    this.currentEndTime = end;
                    if (name && !this.currentShiftName) {
                        this.currentShiftName = name;
                    }
                },

                getUnitName(unitId) {
                    const un = this.units.find(x => String(x.id) === String(unitId));
                    return un ? un.name : 'Unidade';
                },

                getDaySlots(day) {
                    return this.slots
                        .filter(s => Number(s.day_of_week) === Number(day))
                        .sort((a, b) => a.start_time.localeCompare(b.start_time));
                },

                calculateDuration(start, end) {
                    if (!start || !end) return '';
                    const [sh, sm] = start.split(':').map(Number);
                    const [eh, em] = end.split(':').map(Number);
                    const startM = sh * 60 + sm;
                    const endM = eh * 60 + em;
                    const diff = endM - startM;
                    if (diff <= 0) return '';
                    const h = Math.floor(diff / 60);
                    const m = diff % 60;
                    if (h === 0) return `${m}min`;
                    if (m === 0) return `${h}h`;
                    return `${h}h ${m}m`;
                },

                getDayContainerClass(day) {
                    const classes = {
                        1: 'border-blue-200 bg-blue-50/20',
                        2: 'border-indigo-200 bg-indigo-50/20',
                        3: 'border-purple-200 bg-purple-50/20',
                        4: 'border-emerald-200 bg-emerald-50/20',
                        5: 'border-amber-200 bg-amber-50/20',
                        6: 'border-rose-200 bg-rose-50/20',
                        0: 'border-gray-300 bg-gray-50/40',
                    };
                    return classes[day] || 'border-gray-200 bg-white';
                },

                getDayHeaderBorderClass(day) {
                    const classes = {
                        1: 'border-blue-100 bg-blue-50/40',
                        2: 'border-indigo-100 bg-indigo-50/40',
                        3: 'border-purple-100 bg-purple-50/40',
                        4: 'border-emerald-100 bg-emerald-50/40',
                        5: 'border-amber-100 bg-amber-50/40',
                        6: 'border-rose-100 bg-rose-50/40',
                        0: 'border-gray-200 bg-gray-100/50',
                    };
                    return classes[day] || 'border-gray-100';
                },

                getDayDotClass(day) {
                    const classes = {
                        1: 'bg-blue-500',
                        2: 'bg-indigo-500',
                        3: 'bg-purple-500',
                        4: 'bg-emerald-500',
                        5: 'bg-amber-500',
                        6: 'bg-rose-500',
                        0: 'bg-gray-500',
                    };
                    return classes[day] || 'bg-indigo-500';
                },

                getDayTitleClass(day) {
                    const classes = {
                        1: 'text-blue-900',
                        2: 'text-indigo-900',
                        3: 'text-purple-900',
                        4: 'text-emerald-900',
                        5: 'text-amber-900',
                        6: 'text-rose-900',
                        0: 'text-gray-900',
                    };
                    return classes[day] || 'text-gray-900';
                },

                addSlot() {
                    if (!this.userId) {
                        alert('Por favor, selecione um Professor / Colaborador primeiro.');
                        return;
                    }
                    if (this.currentDays.length === 0) {
                        alert('Selecione pelo menos um dia da semana para adicionar a aula.');
                        return;
                    }
                    if (!this.currentStartTime || !this.currentEndTime) {
                        alert('Preencha os horários de início e término.');
                        return;
                    }
                    if (this.currentEndTime <= this.currentStartTime) {
                        alert('O horário de saída (término) deve ser posterior ao horário de entrada (início).');
                        return;
                    }

                    let addedCount = 0;
                    this.currentDays.forEach(day => {
                        const exists = this.slots.some(s =>
                            Number(s.day_of_week) === Number(day) &&
                            s.start_time === this.currentStartTime
                        );

                        if (!exists) {
                            this.slots.push({
                                temp_id: 'slot_' + (this.counter++),
                                day_of_week: Number(day),
                                unit_id: Number(this.currentUnitId || this.defaultUnitId),
                                start_time: this.currentStartTime,
                                end_time: this.currentEndTime,
                                shift_name: this.currentShiftName || '',
                                break_start_time: this.currentBreakStart || null,
                                break_end_time: this.currentBreakEnd || null,
                                tolerance_minutes: Number(this.currentTolerance || 15)
                            });
                            addedCount++;
                        }
                    });

                    if (addedCount === 0) {
                        alert('O horário configurado já está presente neste(s) dia(s) na grade.');
                    }
                },

                removeSlot(tempId) {
                    this.slots = this.slots.filter(s => s.temp_id !== tempId);
                },

                clearAllSlots() {
                    if (confirm('Deseja realmente limpar todos os horários da grade deste professor?')) {
                        this.slots = [];
                    }
                },

                prepareSubmit(e) {
                    if (!this.userId) {
                        e.preventDefault();
                        alert('Selecione o Professor / Colaborador.');
                        return;
                    }
                    if (this.slots.length === 0) {
                        e.preventDefault();
                        alert('Adicione pelo menos um horário de aula à grade antes de salvar.');
                        return;
                    }

                    const hiddenInput = e.target.querySelector('input[name="schedules_json"]');
                    if (hiddenInput) {
                        hiddenInput.value = JSON.stringify(this.slots);
                    }
                }
            };
        }
    </script>

    <div class="w-full max-w-7xl mx-auto space-y-6" x-data="scheduleBuilder(window.__WS_CONFIG__)">

        <!-- Top Navigation -->
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Construtor de Grade</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900">
                    Configurar Grade de Horários do Professor
                </h1>
                <p class="text-xs text-gray-600 mt-0.5">
                    Adicione os horários de aulas e turnos da semana no mesmo formulário e confirme para disponibilizar a grade.
                </p>
            </div>

            <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition flex items-center gap-1.5">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Voltar à Grade Geral
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-xs font-semibold shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold cursor-pointer">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-red-600 text-white p-4 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold cursor-pointer">&times;</button>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs font-medium text-rose-800 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.work-schedules.store') }}" @submit="prepareSubmit($event)">
            @csrf
            <!-- Campo Oculto com o JSON dos Horários -->
            <input type="hidden" name="schedules_json" :value="JSON.stringify(slots)">
            <input type="hidden" name="replace_existing" value="1">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ============================================================= --}}
                {{-- COLUNA DA ESQUERDA (5 COLUNAS): PROFESSOR E CONFIGURADOR DE AULA --}}
                {{-- ============================================================= --}}
                <div class="lg:col-span-5 space-y-5">

                    <!-- Card 1: Seleção do Professor & Unidade Padrão -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                            <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">1</span>
                            <h2 class="text-sm font-bold text-gray-900">Professor & Unidade Principal</h2>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Professor / Colaborador *</label>
                            <select name="user_id" x-model="userId" @change="onUserChange()" required
                                    class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium bg-white">
                                <option value="">Selecione o Professor / Colaborador</option>
                                <template x-for="u in users" :key="u.id">
                                    <option :value="u.id" x-text="u.name + ' (' + u.role + ')'"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Unidade Escolar Padrão *</label>
                            <select x-model="defaultUnitId" @change="onDefaultUnitChange()" required
                                    class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium bg-white">
                                <template x-for="un in units" :key="un.id">
                                    <option :value="un.id" x-text="un.name + (un.city ? ' - ' + un.city : '')"></option>
                                </template>
                            </select>
                        </div>

                        <div class="rounded-2xl bg-indigo-50/70 border border-indigo-100 p-3 text-[11.5px] text-indigo-900 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Horários carregados para o professor: <strong x-text="slots.length"></strong></span>
                            </div>
                            <button type="button" @click="loadUserSchedules(userId)" class="text-indigo-700 hover:text-indigo-900 font-bold underline text-[11px] cursor-pointer">
                                Recarregar
                            </button>
                        </div>
                    </div>

                    <!-- Card 2: Construtor da Aula / Horário (Add Slot) -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">2</span>
                                <h2 class="text-sm font-bold text-gray-900">Configurar Aula / Horário</h2>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500">Preencha e clique em Add</span>
                        </div>

                        <!-- Seleção de Dias da Semana -->
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="text-xs font-semibold text-gray-700 uppercase">Dia(s) da Semana *</label>
                                <div class="flex items-center gap-1.5 text-[11px]">
                                    <button type="button" @click="selectWeekdays()" class="text-indigo-600 hover:underline font-medium">Seg a Sex</button>
                                    <span class="text-gray-300">|</span>
                                    <button type="button" @click="currentDays = []" class="text-gray-400 hover:text-gray-600">Limpar</button>
                                </div>
                            </div>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-1.5">
                                <template x-for="day in [1, 2, 3, 4, 5, 6, 0]" :key="day">
                                    <button type="button"
                                            @click="toggleDay(day)"
                                            :class="currentDays.includes(day)
                                                ? 'bg-indigo-600 text-white border-indigo-600 font-bold shadow-xs'
                                                : 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 font-medium'"
                                            class="rounded-xl border py-2 text-center text-xs transition flex flex-col items-center justify-center">
                                        <span x-text="dayShortNames[day]"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Unidade Desta Aula -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Unidade desta Aula</label>
                            <select x-model="currentUnitId"
                                    class="w-full rounded-2xl border border-gray-300 px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium bg-white">
                                <template x-for="un in units" :key="un.id">
                                    <option :value="un.id" x-text="un.name + (un.city ? ' - ' + un.city : '')"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Atalhos Rápidos de Turno / Horário -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-500 uppercase mb-1.5">Atalhos de Turnos & Aulas Padrão</label>
                            <div class="flex flex-wrap gap-1.5">
                                <button type="button" @click="applyPreset('07:10', '12:35', 'Manhã')"
                                        class="rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-1 text-[11px] font-medium text-indigo-700 hover:bg-indigo-100 transition">
                                    🌅 Manhã Integral (07:10 - 12:35)
                                </button>
                                <button type="button" @click="applyPreset('13:00', '18:20', 'Tarde')"
                                        class="rounded-lg bg-amber-50 border border-amber-200 px-2 py-1 text-[11px] font-medium text-amber-800 hover:bg-amber-100 transition">
                                    ☀️ Tarde Integral (13:00 - 18:20)
                                </button>
                                <button type="button" @click="applyPreset('19:00', '23:00', 'Noite')"
                                        class="rounded-lg bg-purple-50 border border-purple-200 px-2 py-1 text-[11px] font-medium text-purple-700 hover:bg-purple-100 transition">
                                    🌙 Noite Integral (19:00 - 23:00)
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-1 mt-1.5">
                                <button type="button" @click="applyPreset('07:10', '08:00', '1ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">1ª (07:10)</button>
                                <button type="button" @click="applyPreset('08:00', '08:50', '2ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">2ª (08:00)</button>
                                <button type="button" @click="applyPreset('08:50', '09:40', '3ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">3ª (08:50)</button>
                                <button type="button" @click="applyPreset('10:00', '10:50', '4ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">4ª (10:00)</button>
                                <button type="button" @click="applyPreset('10:50', '11:40', '5ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">5ª (10:50)</button>
                                <button type="button" @click="applyPreset('11:40', '12:35', '6ª Aula')" class="rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700 px-1.5 py-0.5 text-[10px] font-mono">6ª (11:40)</button>
                            </div>
                        </div>

                        <!-- Horários de Início e Fim -->
                        <div class="grid grid-cols-2 gap-3 bg-gray-50/80 p-3 rounded-2xl border border-gray-200">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Entrada (Início) *</label>
                                <input type="time" x-model="currentStartTime" required
                                       class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm font-mono text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Saída (Fim) *</label>
                                <input type="time" x-model="currentEndTime" required
                                       class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm font-mono text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                        </div>

                        <!-- Identificação / Disciplina / Matéria -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Identificação / Disciplina / Matéria (Opcional)</label>
                            <input type="text" x-model="currentShiftName"
                                   placeholder="Ex: 1º DS - Programação Web / 2º ADM - Gestão"
                                   class="w-full rounded-2xl border border-gray-300 px-3.5 py-2 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                        </div>

                        <!-- Opções Avançadas: Intervalo & Tolerância (Colapsável) -->
                        <div x-data="{ showAdvanced: false }" class="border-t border-gray-100 pt-2">
                            <button type="button" @click="showAdvanced = !showAdvanced" class="flex items-center gap-1.5 text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                <span x-text="showAdvanced ? '− Ocultar Intervalo e Tolerância' : '+ Definir Intervalo e Tolerância'"></span>
                            </button>

                            <div x-show="showAdvanced" x-cloak class="grid grid-cols-3 gap-2 mt-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Início Intervalo</label>
                                    <input type="time" x-model="currentBreakStart" class="w-full rounded-lg border border-gray-300 px-2 py-1 text-xs font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Fim Intervalo</label>
                                    <input type="time" x-model="currentBreakEnd" class="w-full rounded-lg border border-gray-300 px-2 py-1 text-xs font-mono">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Tolerância (min)</label>
                                    <input type="number" min="0" max="60" x-model="currentTolerance" class="w-full rounded-lg border border-gray-300 px-2 py-1 text-xs font-mono">
                                </div>
                            </div>
                        </div>

                        <!-- Botão Adicionar à Grade -->
                        <div class="pt-2">
                            <button type="button"
                                    @click="addSlot()"
                                    class="w-full rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3 text-xs sm:text-sm shadow-md shadow-indigo-200 hover:shadow-lg transition flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Adicionar Aula / Horário à Grade</span>
                            </button>
                        </div>
                    </div>

                </div>

                {{-- ============================================================= --}}
                {{-- COLUNA DA DIREITA (7 COLUNAS): GRADE SEMANAL EM CONSTRUÇÃO    --}}
                {{-- ============================================================= --}}
                <div class="lg:col-span-7 space-y-5">

                    <div class="rounded-3xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold">3</span>
                                <div>
                                    <h2 class="text-sm font-bold text-gray-900">Grade Semanal do Professor</h2>
                                    <p class="text-[11px] text-gray-500" x-text="selectedUserName ? 'Visualizando grade para: ' + selectedUserName : 'Selecione um professor para começar'"></p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2" x-show="slots.length > 0">
                                <button type="button" @click="clearAllSlots()" class="rounded-xl border border-gray-200 px-3 py-1 text-[11px] font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    Limpar Grade
                                </button>
                            </div>
                        </div>

                        <!-- Visualização da Grade Agrupada por Dias -->
                        <div class="space-y-4">
                            <template x-for="day in [1, 2, 3, 4, 5, 6, 0]" :key="day">
                                <div class="rounded-2xl border transition"
                                     :class="getDaySlots(day).length > 0 ? getDayContainerClass(day) : 'border-dashed border-gray-200 bg-gray-50/40 opacity-75'">

                                    <!-- Cabeçalho do Dia -->
                                    <div class="px-4 py-2.5 flex items-center justify-between border-b"
                                         :class="getDaySlots(day).length > 0 ? getDayHeaderBorderClass(day) : 'border-gray-100'">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full" :class="getDayDotClass(day)"></span>
                                            <span class="text-xs font-bold" :class="getDayTitleClass(day)" x-text="daysList[day]"></span>
                                        </div>
                                        <span class="text-[11px] font-medium"
                                              :class="getDaySlots(day).length > 0 ? 'text-gray-700' : 'text-gray-400'"
                                              x-text="getDaySlots(day).length + ' aula(s)'"></span>
                                    </div>

                                    <!-- Slots / Aulas do Dia -->
                                    <div class="p-3">
                                        <template x-if="getDaySlots(day).length === 0">
                                            <div class="py-2 text-center text-xs text-gray-400 italic">
                                                Nenhum horário cadastrado para este dia.
                                            </div>
                                        </template>

                                        <div class="space-y-2">
                                            <template x-for="slot in getDaySlots(day)" :key="slot.temp_id">
                                                <div class="relative rounded-xl border border-gray-200 bg-white p-3 shadow-2xs hover:shadow-xs transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5">
                                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                                        <!-- Horário Badge -->
                                                        <div class="rounded-lg bg-indigo-50 border border-indigo-200/80 px-2.5 py-1 text-center flex-shrink-0">
                                                            <div class="text-xs font-mono font-bold text-indigo-900" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                                                            <div class="text-[9.5px] font-semibold text-indigo-600" x-text="calculateDuration(slot.start_time, slot.end_time)"></div>
                                                        </div>

                                                        <!-- Detalhes da Aula -->
                                                        <div class="min-w-0 flex-1">
                                                            <div class="text-xs font-bold text-gray-900 truncate" x-text="slot.shift_name || 'Aula / Horário de Trabalho'"></div>
                                                            <div class="flex flex-wrap items-center gap-1.5 mt-0.5 text-[11px] text-gray-500">
                                                                <span class="inline-flex items-center gap-1 font-medium text-gray-700 bg-gray-100 px-1.5 py-0.5 rounded">
                                                                    <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                                                    <span x-text="getUnitName(slot.unit_id)"></span>
                                                                </span>
                                                                <template x-if="slot.break_start_time && slot.break_end_time">
                                                                    <span class="text-[10px] text-amber-700 bg-amber-50 px-1.5 py-0.5 rounded border border-amber-200">
                                                                        Intervalo: <span x-text="slot.break_start_time + ' - ' + slot.break_end_time"></span>
                                                                    </span>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Botão Remover Slot Individual -->
                                                    <button type="button"
                                                            @click="removeSlot(slot.temp_id)"
                                                            class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-rose-100 text-gray-400 hover:text-rose-600 flex items-center justify-center transition flex-shrink-0"
                                                            title="Remover horário">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Barra de Resumo & Estatísticas da Grade -->
                        <div class="rounded-2xl bg-indigo-900 text-white p-4 sm:p-5 shadow-sm space-y-3">
                            <div class="flex items-center justify-between border-b border-indigo-800/80 pb-2.5">
                                <span class="text-xs font-semibold uppercase tracking-wider text-indigo-200">Resumo da Grade Semanal</span>
                                <span class="text-xs font-bold text-indigo-300" x-text="slots.length + ' Horário(s) Configurado(s)'"></span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="bg-indigo-800/50 rounded-xl p-2.5">
                                    <div class="text-[10px] uppercase text-indigo-300 font-semibold">Total de Aulas</div>
                                    <div class="text-lg sm:text-xl font-extrabold text-white" x-text="slots.length"></div>
                                </div>
                                <div class="bg-indigo-800/50 rounded-xl p-2.5">
                                    <div class="text-[10px] uppercase text-indigo-300 font-semibold">Dias com Aula</div>
                                    <div class="text-lg sm:text-xl font-extrabold text-white" x-text="uniqueDaysCount"></div>
                                </div>
                                <div class="bg-indigo-800/50 rounded-xl p-2.5">
                                    <div class="text-[10px] uppercase text-indigo-300 font-semibold">Carga Horária</div>
                                    <div class="text-sm sm:text-base font-extrabold text-emerald-300 mt-1" x-text="totalHoursFormatted"></div>
                                </div>
                            </div>

                            <!-- Botão de Confirmação e Submissão Final -->
                            <div class="pt-2 flex items-center justify-end gap-3">
                                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl border border-indigo-700 bg-indigo-800/60 px-4 py-2.5 text-xs font-medium text-indigo-100 hover:bg-indigo-700 transition">
                                    Cancelar
                                </a>
                                <button type="submit"
                                        :disabled="!userId || slots.length === 0"
                                        :class="(!userId || slots.length === 0) ? 'opacity-50 cursor-not-allowed bg-gray-500' : 'bg-emerald-500 hover:bg-emerald-400 shadow-lg shadow-emerald-900/40'"
                                        class="rounded-xl px-6 py-2.5 text-xs sm:text-sm font-extrabold text-white transition flex items-center gap-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                    <span>Confirmar e Salvar Grade Completa</span>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </div>

    @else
    {{-- ========================================================================= --}}
    {{-- MODO EDIÇÃO INDIVIDUAL DE HORÁRIO ESPECÍFICO                              --}}
    {{-- ========================================================================= --}}
    <div class="w-full max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Editar Horário</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-semibold tracking-tight text-gray-900">
                    Editar Horário de Trabalho
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.work-schedules.create', ['user_id' => $schedule->user_id]) }}" class="rounded-xl bg-indigo-50 border border-indigo-200 px-3.5 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                    + Abrir Construtor de Grade
                </a>
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    Voltar
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-xs font-medium text-rose-800 space-y-1">
                @foreach($errors->all() as $err)
                    <div>• {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.work-schedules.update', $schedule) }}"
              class="rounded-3xl border border-gray-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
            @csrf
            @method('PUT')

            <!-- 1. Professor & Escola -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Professor / Colaborador *</label>
                    <select name="user_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ (old('user_id', $schedule->user_id) == $u->id) ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role ?? 'Docente' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Unidade Escolar *</label>
                    <select name="unit_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ (old('unit_id', $schedule->unit_id) == $un->id) ? 'selected' : '' }}>
                                {{ $un->name }} ({{ $un->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- 2. Dia da Semana -->
            <div>
                <label class="block text-xs font-medium text-gray-700 uppercase mb-2">Dia da Semana *</label>
                <select name="day_of_week" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-medium">
                    @foreach($daysList as $num => $dayName)
                        <option value="{{ $num }}" {{ (old('day_of_week', $schedule->day_of_week) == $num) ? 'selected' : '' }}>{{ $dayName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 3. Descrição do Turno -->
            <div>
                <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Identificação do Turno / Matéria (Opcional)</label>
                <input type="text" name="shift_name" value="{{ old('shift_name', $schedule->shift_name) }}"
                       placeholder="Ex: Manhã - Técnico em Informática / Tarde - Ensino Médio"
                       class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-normal">
            </div>

            <!-- 4. Horários de Entrada e Saída -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-200">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Horário de Entrada *</label>
                    <input type="time" name="start_time" required
                           value="{{ old('start_time', $schedule->start_time ? substr($schedule->start_time, 0, 5) : '07:10') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Horário de Saída *</label>
                    <input type="time" name="end_time" required
                           value="{{ old('end_time', $schedule->end_time ? substr($schedule->end_time, 0, 5) : '12:35') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <!-- 5. Intervalo & Tolerância -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Início do Intervalo</label>
                    <input type="time" name="break_start_time"
                           value="{{ old('break_start_time', $schedule->break_start_time ? substr($schedule->break_start_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Fim do Intervalo</label>
                    <input type="time" name="break_end_time"
                           value="{{ old('break_end_time', $schedule->break_end_time ? substr($schedule->break_end_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>

                <div>
                    <label class="block text-xs font-medium text-gray-700 uppercase mb-1.5">Tolerância (minutos)</label>
                    <input type="number" name="tolerance_minutes" min="0" max="60"
                           value="{{ old('tolerance_minutes', $schedule->tolerance_minutes ?? 15) }}"
                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 font-mono focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-gray-100">
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-2xl border border-gray-300 px-5 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="rounded-2xl bg-indigo-600 px-6 py-2.5 text-xs font-semibold text-white hover:bg-indigo-500 shadow-md shadow-indigo-200 transition">
                    Atualizar Horário
                </button>
            </div>

        </form>

    </div>
    @endif

</div>
@endsection
