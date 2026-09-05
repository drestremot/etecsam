@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">

    @if($action === 'create')
    {{-- ========================================================================= --}}
    {{-- CONSTRUTOR INTERATIVO DE GRADE COMPLETA (MODO CRIAÇÃO)                   --}}
    {{-- ========================================================================= --}}
    <script>
        window.__WS_CONFIG__ = {
            users: {!! json_encode($usersData ?? $users->map(fn($u) => [
                'id'                 => $u->id,
                'name'               => $u->name,
                'role'               => $u->role ?? 'Docente',
                'schedule_role_type' => $u->schedule_role_type,
                'teacher_color'      => $u->teacher_color,
                'assigned_subjects'  => $u->assigned_subjects->map(fn($s) => [
                    'id'           => $s->id,
                    'name'         => $s->name,
                    'course_id'    => $s->course_id,
                    'course_title' => $s->course?->title ?? '',
                    'semester'     => $s->semester ?? '',
                    'workload'     => $s->workload ?? '',
                ])->values(),
            ])->values()) !!},
            units: {!! json_encode($units->map(fn($un) => ['id' => $un->id, 'name' => $un->name, 'city' => $un->city])->values()) !!},
            courses: {!! json_encode($courses->map(fn($c) => ['id' => $c->id, 'title' => $c->title, 'type' => $c->type, 'unit_id' => $c->unit_id])->values()) !!},
            daysList: {!! json_encode($daysList) !!},
            dayColorConfigs: {!! json_encode($dayColorConfigs ?? \App\Models\WorkSchedule::getDayColorConfig()) !!},
            subjects: {!! json_encode($subjects ?? []) !!},
            oldUserId: '{{ old('user_id', request('user_id', $initialUserId ?? '')) }}',
            oldUnitId: '{{ old('unit_id', $units->first()?->id ?? '') }}',
            allUserSchedules: {!! json_encode($allUserSchedules ?? []) !!}
        };

        function scheduleBuilder(config) {
            return {
                users: config.users || [],
                units: config.units || [],
                courses: config.courses || [],
                daysList: config.daysList || {},
                dayColorConfigs: config.dayColorConfigs || {},
                subjects: config.subjects || [],
                dayShortNames: { 1: 'Seg', 2: 'Ter', 3: 'Qua', 4: 'Qui', 5: 'Sex', 6: 'Sáb', 0: 'Dom' },
                allUserSchedules: config.allUserSchedules || {},

                userId: config.oldUserId || (config.users[0]?.id || ''),
                defaultUnitId: config.oldUnitId || (config.units[0]?.id || ''),

                currentScheduleType: 'class', // 'class', 'coordination', 'administrative'
                currentDays: [1],
                currentUnitId: config.oldUnitId || (config.units[0]?.id || ''),
                currentStartTime: '07:10',
                currentEndTime: '12:35',
                currentShiftName: '',
                
                // Atribuições Acadêmicas (Docente)
                currentCourseId: '',
                currentCourseName: '',
                currentSubjectId: '',
                currentSubjectName: '',
                currentDivision: '',
                currentClassName: '',
                currentClassroom: '',
                
                // Modo de seleção de disciplinas: 'assigned' (atribuídas ao docente) ou 'all' (geral de cursos)
                subjectSelectionMode: 'assigned',
                
                currentBreakStart: '',
                currentBreakEnd: '',
                currentTolerance: 15,

                slots: [],
                counter: 1,

                init() {
                    if (this.userId) {
                        this.syncUserRoleType();
                        this.loadUserSchedules(this.userId);
                    }
                },

                get selectedUser() {
                    return this.users.find(x => String(x.id) === String(this.userId)) || null;
                },

                get selectedUserName() {
                    return this.selectedUser ? this.selectedUser.name : '';
                },

                get selectedUserColor() {
                    return this.selectedUser?.teacher_color || {
                        dot: '#2563eb',
                        bg: '#eff6ff',
                        border: '#bfdbfe',
                        text: '#1d4ed8',
                        badge: 'bg-blue-50 text-blue-700 border-blue-200'
                    };
                },

                get isTeacherUser() {
                    const u = this.selectedUser;
                    return !u || u.schedule_role_type === 'teacher';
                },

                get userAssignedSubjects() {
                    return this.selectedUser?.assigned_subjects || [];
                },

                get availableCourses() {
                    if (!this.currentUnitId) return this.courses;
                    const byUnit = this.courses.filter(c => Number(c.unit_id) === Number(this.currentUnitId));
                    return byUnit.length > 0 ? byUnit : this.courses;
                },

                get filteredSubjects() {
                    if (!this.currentCourseId) {
                        return this.subjects;
                    }
                    return this.subjects.filter(s => Number(s.course_id) === Number(this.currentCourseId));
                },

                get uniqueDaysCount() {
                    const unique = new Set(this.slots.map(s => s.day_of_week));
                    return unique.size;
                },

                get totalHoursFormatted() {
                    let totalMinutes = 0;
                    this.slots.forEach(s => {
                        const [sh, sm] = (s.start_time || '00:00').split(':').map(Number);
                        const [eh, em] = (s.end_time || '00:00').split(':').map(Number);
                        const startM = sh * 60 + sm;
                        const endM = eh * 60 + em;
                        if (endM > startM) {
                            let duration = (endM - startM);
                            if (s.break_start_time && s.break_end_time) {
                                const [bsh, bsm] = s.break_start_time.split(':').map(Number);
                                const [beh, bem] = s.break_end_time.split(':').map(Number);
                                const bStartM = bsh * 60 + bsm;
                                const bEndM = beh * 60 + bem;
                                if (bEndM > bStartM) {
                                    duration -= (bEndM - bStartM);
                                }
                            }
                            totalMinutes += Math.max(0, duration);
                        }
                    });

                    const h = Math.floor(totalMinutes / 60);
                    const m = totalMinutes % 60;
                    if (h === 0 && m === 0) return '0h';
                    if (m === 0) return `${h}h semanais`;
                    return `${h}h ${m}min semanais`;
                },

                syncUserRoleType() {
                    const u = this.selectedUser;
                    if (!u) return;
                    if (u.schedule_role_type === 'coordinator') {
                        this.currentScheduleType = 'coordination';
                        this.currentShiftName = 'Coordenação Pedagógica';
                    } else if (u.schedule_role_type === 'staff') {
                        this.currentScheduleType = 'administrative';
                        this.currentShiftName = 'Expediente Administrativo';
                    } else {
                        this.currentScheduleType = 'class';
                        this.currentShiftName = '';
                        // Se o professor tiver disciplinas atribuídas, seleciona o modo 'assigned'
                        if (this.userAssignedSubjects.length > 0) {
                            this.subjectSelectionMode = 'assigned';
                        }
                    }
                },

                onUserChange() {
                    this.slots = [];
                    this.currentCourseId = '';
                    this.currentCourseName = '';
                    this.currentSubjectId = '';
                    this.currentSubjectName = '';
                    this.currentDivision = '';
                    this.currentClassName = '';
                    this.currentClassroom = '';
                    this.syncUserRoleType();
                    if (this.userId) {
                        this.loadUserSchedules(this.userId);
                    }
                },

                loadUserSchedules(userId) {
                    if (!userId) {
                        this.slots = [];
                        return;
                    }

                    if (this.allUserSchedules && this.allUserSchedules[userId]) {
                        const existing = this.allUserSchedules[userId];
                        this.slots = existing.map(item => ({
                            temp_id: 'slot_' + (this.counter++),
                            day_of_week: Number(item.day_of_week),
                            unit_id: Number(item.unit_id),
                            course_id: item.course_id ? Number(item.course_id) : null,
                            course_name: item.course_name || '',
                            subject_id: item.subject_id ? Number(item.subject_id) : null,
                            start_time: item.start_time ? item.start_time.substring(0, 5) : '07:10',
                            end_time: item.end_time ? item.end_time.substring(0, 5) : '12:35',
                            shift_name: item.shift_name || '',
                            subject_name: item.subject_name || '',
                            class_name: item.class_name || '',
                            division: item.division || '',
                            classroom: item.classroom || '',
                            schedule_type: item.schedule_type || 'class',
                            break_start_time: item.break_start_time ? item.break_start_time.substring(0, 5) : null,
                            break_end_time: item.break_end_time ? item.break_end_time.substring(0, 5) : null,
                            tolerance_minutes: item.tolerance_minutes || 15
                        }));
                        return;
                    }

                    fetch(`/admin/work-schedules/user/${userId}`)
                        .then(r => r.json())
                        .then(data => {
                            const schedList = Array.isArray(data) ? data : (data.schedules || []);
                            if (Array.isArray(schedList) && schedList.length > 0) {
                                this.slots = schedList.map(item => ({
                                    temp_id: 'slot_' + (this.counter++),
                                    day_of_week: Number(item.day_of_week),
                                    unit_id: Number(item.unit_id),
                                    course_id: item.course_id ? Number(item.course_id) : null,
                                    course_name: item.course_name || item.course?.title || '',
                                    subject_id: item.subject_id ? Number(item.subject_id) : null,
                                    start_time: item.start_time ? item.start_time.substring(0, 5) : '07:10',
                                    end_time: item.end_time ? item.end_time.substring(0, 5) : '12:35',
                                    shift_name: item.shift_name || '',
                                    subject_name: item.subject_name || '',
                                    class_name: item.class_name || '',
                                    division: item.division || '',
                                    classroom: item.classroom || '',
                                    schedule_type: item.schedule_type || 'class',
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

                selectAssignedSubject(subId) {
                    if (!subId) {
                        this.currentSubjectId = '';
                        this.currentSubjectName = '';
                        return;
                    }
                    const sub = this.userAssignedSubjects.find(s => String(s.id) === String(subId));
                    if (sub) {
                        this.currentSubjectId = sub.id;
                        this.currentSubjectName = sub.name;
                        if (sub.course_id) {
                            this.currentCourseId = sub.course_id;
                            this.currentCourseName = sub.course_title || this.getCourseName(sub.course_id);
                        }

                        // Detecção automática de Turma A ou B no título
                        if (sub.name.includes('(A)') || sub.name.toUpperCase().includes('TURMA A')) {
                            this.currentDivision = 'A';
                        } else if (sub.name.includes('(B)') || sub.name.toUpperCase().includes('TURMA B')) {
                            this.currentDivision = 'B';
                        } else {
                            this.currentDivision = '';
                        }

                        // Sugere o nome da turma / módulo
                        if (sub.course_title) {
                            const parts = sub.course_title.split('-');
                            if (parts.length > 1) {
                                this.currentClassName = parts[parts.length - 1].trim();
                            }
                        }
                    }
                },

                onCourseChange() {
                    if (this.currentCourseId) {
                        const course = this.courses.find(c => String(c.id) === String(this.currentCourseId));
                        if (course) {
                            this.currentCourseName = course.title;
                            if (!this.currentClassName && course.title.includes('Módulo/Série')) {
                                const parts = course.title.split('-');
                                if (parts.length > 1) {
                                    this.currentClassName = parts[parts.length - 1].trim();
                                }
                            }
                        }
                    } else {
                        this.currentCourseName = '';
                    }
                    this.currentSubjectId = '';
                    this.currentSubjectName = '';
                },

                onGeneralSubjectChange() {
                    if (this.currentSubjectId) {
                        const sub = this.subjects.find(s => String(s.id) === String(this.currentSubjectId));
                        if (sub) {
                            this.currentSubjectName = sub.name;
                            if (sub.course_id && !this.currentCourseId) {
                                this.currentCourseId = sub.course_id;
                                const course = this.courses.find(c => String(c.id) === String(sub.course_id));
                                if (course) this.currentCourseName = course.title;
                            }
                            if (sub.name.includes('(A)') || sub.name.toUpperCase().includes('TURMA A')) {
                                this.currentDivision = 'A';
                            } else if (sub.name.includes('(B)') || sub.name.toUpperCase().includes('TURMA B')) {
                                this.currentDivision = 'B';
                            }
                        }
                    }
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

                appendTurmaDivision(div) {
                    this.currentDivision = div;
                    let sub = this.currentSubjectName.trim();
                    sub = sub.replace(/\s*\(?(Turma\s*)?[AB]\)?\s*$/i, '').trim();
                    if (div === 'A') {
                        this.currentSubjectName = sub ? `${sub} (A)` : 'Turma (A)';
                    } else if (div === 'B') {
                        this.currentSubjectName = sub ? `${sub} (B)` : 'Turma (B)';
                    } else {
                        this.currentSubjectName = sub;
                    }
                },

                getUnitName(unitId) {
                    const un = this.units.find(x => String(x.id) === String(unitId));
                    return un ? un.name : 'Unidade';
                },

                getCourseName(courseId) {
                    const c = this.courses.find(x => String(x.id) === String(courseId));
                    return c ? c.title : '';
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

                getDayColor(day) {
                    return this.dayColorConfigs[day] || {
                        name: 'Dia',
                        short: 'DIA',
                        badge_class: 'bg-gray-100 text-gray-700 border-gray-200',
                        hex: '#64748b',
                        border_hex: '#94a3b8',
                        light_bg: '#f8fafc',
                    };
                },

                addSlot() {
                    if (!this.userId) {
                        alert('Por favor, selecione um Professor / Colaborador primeiro.');
                        return;
                    }
                    if (this.currentDays.length === 0) {
                        alert('Selecione pelo menos um dia da semana para adicionar.');
                        return;
                    }
                    if (!this.currentStartTime || !this.currentEndTime) {
                        alert('Preencha os horários de início e término.');
                        return;
                    }
                    if (this.currentEndTime <= this.currentStartTime) {
                        alert('O horário de término deve ser posterior ao horário de início.');
                        return;
                    }

                    if (this.currentScheduleType === 'class' && !this.currentSubjectName.trim() && !this.currentCourseId) {
                        alert('Por favor, selecione ou informe o Curso e a Disciplina da aula.');
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
                                course_id: this.currentScheduleType === 'class' && this.currentCourseId ? Number(this.currentCourseId) : null,
                                course_name: this.currentScheduleType === 'class' ? (this.currentCourseName || this.getCourseName(this.currentCourseId)) : '',
                                subject_id: this.currentScheduleType === 'class' && this.currentSubjectId ? Number(this.currentSubjectId) : null,
                                start_time: this.currentStartTime,
                                end_time: this.currentEndTime,
                                shift_name: this.currentShiftName || '',
                                subject_name: this.currentScheduleType === 'class' ? (this.currentSubjectName || '') : '',
                                class_name: this.currentScheduleType === 'class' ? (this.currentClassName || '') : '',
                                division: this.currentScheduleType === 'class' ? (this.currentDivision || '') : '',
                                classroom: this.currentScheduleType === 'class' ? (this.currentClassroom || '') : '',
                                schedule_type: this.currentScheduleType,
                                break_start_time: this.currentBreakStart || null,
                                break_end_time: this.currentBreakEnd || null,
                                tolerance_minutes: Number(this.currentTolerance || 15)
                            });
                            addedCount++;
                        }
                    });

                    if (addedCount === 0) {
                        alert('Os horários informados já existem na grade para os dias selecionados.');
                    }
                },

                removeSlot(tempId) {
                    this.slots = this.slots.filter(s => s.temp_id !== tempId);
                },

                clearAllSlots() {
                    if (confirm('Deseja realmente limpar todos os horários da grade deste usuário?')) {
                        this.slots = [];
                    }
                },

                prepareSubmit(event) {
                    if (!this.userId) {
                        event.preventDefault();
                        alert('Selecione um Professor / Colaborador antes de salvar.');
                        return false;
                    }
                    if (this.slots.length === 0) {
                        event.preventDefault();
                        alert('Adicione pelo menos um horário na grade semanal antes de salvar.');
                        return false;
                    }
                    document.getElementById('schedules_json').value = JSON.stringify(this.slots);
                    return true;
                }
            };
        }
    </script>

    <div x-data="scheduleBuilder(window.__WS_CONFIG__)" class="max-w-7xl mx-auto space-y-6">

        <!-- Top Breadcrumbs & Action Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Painel</a>
                    <span>/</span>
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-gray-800 font-semibold">Construtor Interativo</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm shadow-md shadow-indigo-200">
                        📅
                    </span>
                    <span>Montar Grade de Horários do Docente</span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5">
                    O sistema busca automaticamente as disciplinas atribuídas ao docente para montar a grade e publicar aos alunos.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.work-schedules.print') }}" class="rounded-xl bg-teal-600 hover:bg-teal-500 text-white px-3.5 py-2 text-xs font-semibold shadow-2xs transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Imprimir / Publicação Alunos</span>
                </a>
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    <span>Voltar à Lista</span>
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
                <div class="font-bold">Atenção aos seguintes pontos:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulário Principal com JSON sincronizado -->
        <form method="POST" action="{{ route('admin.work-schedules.store') }}" @submit="prepareSubmit($event)">
            @csrf
            <input type="hidden" name="schedules_json" id="schedules_json">
            <input type="hidden" name="user_id" :value="userId">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- ============================================================= --}}
                {{-- COLUNA DA ESQUERDA (5 COLUNAS): SELEÇÃO & CONSTRUTOR DE AULA  --}}
                {{-- ============================================================= --}}
                <div class="lg:col-span-5 space-y-5">

                    <!-- Card 1: Seleção do Professor & Unidade Padrão -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">1</span>
                                <h2 class="text-sm font-bold text-gray-900">Docente / Colaborador</h2>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500">Passo Inicial</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Professor / Colaborador *</label>
                            <select x-model="userId" @change="onUserChange()" required
                                    class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 font-semibold bg-white shadow-2xs">
                                <option value="">Selecione o Usuário</option>
                                <template x-for="u in users" :key="u.id">
                                    <option :value="u.id" x-text="u.name + ' (' + u.role + ')' + (u.assigned_subjects?.length ? ' • ' + u.assigned_subjects.length + ' disc.' : '')"></option>
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

                        <!-- Badge com Cor Exclusiva do Docente e Resumo de Disciplinas Atribuídas -->
                        <template x-if="selectedUser">
                            <div class="rounded-2xl p-3 text-xs space-y-2 border"
                                 :style="'background-color: ' + selectedUserColor.bg + '; border-color: ' + selectedUserColor.border + '; color: ' + selectedUserColor.text">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="w-3.5 h-3.5 rounded-full shadow-2xs" :style="'background-color: ' + selectedUserColor.dot"></span>
                                        <div>
                                            <div class="font-bold" x-text="selectedUserName"></div>
                                            <div class="text-[11px] opacity-80" x-text="'Perfil: ' + selectedUser.role"></div>
                                        </div>
                                    </div>
                                    <span class="text-[11px] font-bold px-2 py-0.5 rounded-lg bg-white/70 shadow-2xs" x-text="slots.length + ' horário(s)'"></span>
                                </div>

                                <template x-if="userAssignedSubjects.length > 0">
                                    <div class="pt-1.5 border-t border-black/10 flex items-center justify-between text-[11px]">
                                        <span class="font-bold">✨ Disciplinas no Sistema:</span>
                                        <span class="font-extrabold px-1.5 py-0.5 rounded bg-white/80" x-text="userAssignedSubjects.length + ' atribuída(s)'"></span>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <!-- Card 2: Construtor da Aula / Horário (Add Slot) -->
                    <div class="rounded-3xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-xs font-bold">2</span>
                                <h2 class="text-sm font-bold text-gray-900">Configurar Horário / Aula</h2>
                            </div>
                            <span class="text-[11px] font-medium text-gray-500">Adicionar à Grade</span>
                        </div>

                        <!-- Seletor do Tipo de Horário (Docente / Coordenação / Administrativo) -->
                        <div>
                            <label class="block text-[11px] font-semibold text-gray-600 uppercase mb-1.5">Tipo de Atividade</label>
                            <div class="grid grid-cols-3 gap-1.5 p-1 bg-gray-100 rounded-2xl">
                                <button type="button" @click="currentScheduleType = 'class'"
                                        :class="currentScheduleType === 'class' ? 'bg-white text-indigo-700 font-bold shadow-2xs' : 'text-gray-600 hover:text-gray-900'"
                                        class="py-1.5 text-center text-xs rounded-xl transition cursor-pointer">
                                    👨‍🏫 Aula
                                </button>
                                <button type="button" @click="currentScheduleType = 'coordination'; if(!currentShiftName) currentShiftName = 'Coordenação Pedagógica'"
                                        :class="currentScheduleType === 'coordination' ? 'bg-white text-purple-700 font-bold shadow-2xs' : 'text-gray-600 hover:text-gray-900'"
                                        class="py-1.5 text-center text-xs rounded-xl transition cursor-pointer">
                                    📋 Coordenação
                                </button>
                                <button type="button" @click="currentScheduleType = 'administrative'; if(!currentShiftName) currentShiftName = 'Expediente Administrativo'"
                                        :class="currentScheduleType === 'administrative' ? 'bg-white text-slate-800 font-bold shadow-2xs' : 'text-gray-600 hover:text-gray-900'"
                                        class="py-1.5 text-center text-xs rounded-xl transition cursor-pointer">
                                    🏢 Expediente
                                </button>
                            </div>
                        </div>

                        <!-- Seleção de Dias da Semana (Com Cores Temáticas) -->
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
                                            :style="currentDays.includes(day) ? ('background-color: ' + getDayColor(day).hex + '; border-color: ' + getDayColor(day).hex + '; color: #ffffff;') : ''"
                                            :class="currentDays.includes(day)
                                                ? 'font-bold shadow-sm'
                                                : 'bg-white border-gray-300 text-gray-700 hover:bg-gray-50 font-medium'"
                                            class="py-2 text-center text-xs rounded-xl border transition cursor-pointer flex flex-col items-center justify-center">
                                        <span class="text-[11px]" x-text="dayShortNames[day]"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Horários de Início & Término com Atalhos Rápidos de Turnos -->
                        <div class="space-y-2">
                            <div>
                                <label class="block text-[11px] font-semibold text-gray-500 uppercase mb-1.5">Atalhos de Turnos & Horários</label>
                                <div class="flex flex-wrap gap-1.5">
                                    <button type="button" @click="applyPreset('07:10', '12:35', 'Manhã')"
                                            class="rounded-lg bg-blue-50 border border-blue-200 px-2 py-1 text-[11px] font-medium text-blue-800 hover:bg-blue-100 transition">
                                        🌅 Manhã (07:10 - 12:35)
                                    </button>
                                    <button type="button" @click="applyPreset('13:00', '18:20', 'Tarde')"
                                            class="rounded-lg bg-amber-50 border border-amber-200 px-2 py-1 text-[11px] font-medium text-amber-800 hover:bg-amber-100 transition">
                                        ☀️ Tarde (13:00 - 18:20)
                                    </button>
                                    <button type="button" @click="applyPreset('19:00', '22:50', 'Noite')"
                                            class="rounded-lg bg-purple-50 border border-purple-200 px-2 py-1 text-[11px] font-medium text-purple-800 hover:bg-purple-100 transition">
                                        🌙 Noite (19:00 - 22:50)
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Início *</label>
                                    <input type="time" x-model="currentStartTime" required
                                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm font-mono font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Término *</label>
                                    <input type="time" x-model="currentEndTime" required
                                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm font-mono font-bold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                </div>
                            </div>
                        </div>

                        {{-- ============================================================= --}}
                        {{-- SEÇÃO 1: BUSCA AUTOMÁTICA DE DISCIPLINAS DO PROFESSOR (DOCENTE) --}}
                        {{-- ============================================================= --}}
                        <template x-if="currentScheduleType === 'class'">
                            <div class="space-y-3.5 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                
                                <div class="flex items-center justify-between border-b border-indigo-100 pb-2">
                                    <span class="text-xs font-bold text-indigo-950 uppercase tracking-wide flex items-center gap-1.5">
                                        📚 Disciplinas do Docente
                                    </span>
                                    
                                    <!-- Alternador de Modo: Atribuídas vs Todas -->
                                    <div class="flex items-center gap-1 text-[10px]">
                                        <button type="button" @click="subjectSelectionMode = 'assigned'"
                                                :class="subjectSelectionMode === 'assigned' ? 'bg-indigo-600 text-white font-bold' : 'bg-white text-indigo-700 hover:bg-indigo-100'"
                                                class="rounded px-2 py-0.5 border border-indigo-200 transition">
                                            Atribuídas (<span x-text="userAssignedSubjects.length"></span>)
                                        </button>
                                        <button type="button" @click="subjectSelectionMode = 'all'"
                                                :class="subjectSelectionMode === 'all' ? 'bg-indigo-600 text-white font-bold' : 'bg-white text-indigo-700 hover:bg-indigo-100'"
                                                class="rounded px-2 py-0.5 border border-indigo-200 transition">
                                            Ver Todos os Cursos
                                        </button>
                                    </div>
                                </div>

                                {{-- MODO 1: DISCIPLINAS ATRIBUÍDAS AO DOCENTE (PADRÃO RECOMENDADO) --}}
                                <template x-if="subjectSelectionMode === 'assigned'">
                                    <div class="space-y-2">
                                        <label class="block text-[11px] font-bold text-indigo-900 uppercase">
                                            Selecione a Disciplina Atribuída a este Docente *
                                        </label>

                                        <template x-if="userAssignedSubjects.length > 0">
                                            <div>
                                                <select @change="selectAssignedSubject($event.target.value)"
                                                        class="w-full rounded-xl border border-indigo-300 px-3 py-2.5 text-xs font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white shadow-2xs">
                                                    <option value="">-- Escolha uma das disciplinas atribuídas ao docente --</option>
                                                    <template x-for="sub in userAssignedSubjects" :key="'assigned-'+sub.id">
                                                        <option :value="sub.id" :selected="String(currentSubjectId) === String(sub.id)"
                                                                x-text="sub.name + (sub.course_title ? ' ➔ Curso: ' + sub.course_title : '')"></option>
                                                    </template>
                                                </select>
                                                <p class="text-[10.5px] text-indigo-700 mt-1 font-medium">
                                                    💡 Ao selecionar, o curso, disciplina e divisão são preenchidos automaticamente.
                                                </p>
                                            </div>
                                        </template>

                                        <template x-if="userAssignedSubjects.length === 0">
                                            <div class="rounded-xl bg-amber-50 border border-amber-200 p-2.5 text-[11px] text-amber-800 space-y-1">
                                                <div class="font-bold">Nenhuma disciplina vinculada previamente a este docente.</div>
                                                <p>Você pode selecionar o curso e a disciplina diretamente abaixo:</p>
                                                <button type="button" @click="subjectSelectionMode = 'all'" class="text-xs font-bold text-indigo-700 hover:underline">
                                                    ➔ Abrir catálogo completo de Cursos e Disciplinas
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                {{-- MODO 2: SELEÇÃO MANUAL / COMPLETA DE TODOS OS CURSOS --}}
                                <template x-if="subjectSelectionMode === 'all' || userAssignedSubjects.length === 0">
                                    <div class="space-y-3 pt-1">
                                        <!-- Seleção de Curso -->
                                        <div>
                                            <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Curso *</label>
                                            <select x-model="currentCourseId" @change="onCourseChange()"
                                                    class="w-full rounded-xl border border-indigo-200 px-3 py-2 text-xs font-medium text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                                <option value="">-- Selecione o Curso --</option>
                                                <template x-for="c in availableCourses" :key="c.id">
                                                    <option :value="c.id" x-text="c.title + (c.type ? ' (' + c.type + ')' : '')"></option>
                                                </template>
                                            </select>
                                        </div>

                                        <!-- Seleção de Disciplina do Curso -->
                                        <template x-if="filteredSubjects.length > 0">
                                            <div>
                                                <label class="block text-[11px] font-bold text-indigo-900 uppercase mb-1">Disciplinas do Curso</label>
                                                <select x-model="currentSubjectId" @change="onGeneralSubjectChange()"
                                                        class="w-full rounded-xl border border-indigo-200 px-3 py-2 text-xs font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                                    <option value="">Selecione na lista de disciplinas do curso...</option>
                                                    <template x-for="s in filteredSubjects" :key="s.id">
                                                        <option :value="s.id" x-text="s.name + (s.semester ? ' (' + s.semester + ')' : '')"></option>
                                                    </template>
                                                </select>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <!-- Nome da Disciplina & Divisão de Turma -->
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <label class="text-[11px] font-bold text-indigo-900 uppercase">Nome da Disciplina *</label>
                                        
                                        <!-- Botões Rápidos de Divisão Turma A / Turma B -->
                                        <div class="flex items-center gap-1">
                                            <span class="text-[10px] text-gray-500 font-medium">Turma:</span>
                                            <button type="button" @click="appendTurmaDivision('A')"
                                                    :class="currentDivision === 'A' ? 'bg-sky-600 text-white font-bold' : 'bg-sky-50 text-sky-800 border-sky-300 hover:bg-sky-100'"
                                                    class="rounded border px-1.5 py-0.5 text-[10px] font-semibold transition cursor-pointer">
                                                (A)
                                            </button>
                                            <button type="button" @click="appendTurmaDivision('B')"
                                                    :class="currentDivision === 'B' ? 'bg-orange-600 text-white font-bold' : 'bg-orange-50 text-orange-800 border-orange-300 hover:bg-orange-100'"
                                                    class="rounded border px-1.5 py-0.5 text-[10px] font-semibold transition cursor-pointer">
                                                (B)
                                            </button>
                                            <button type="button" @click="appendTurmaDivision('')"
                                                    :class="!currentDivision ? 'bg-gray-700 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                                    class="rounded px-1.5 py-0.5 text-[10px] font-medium transition cursor-pointer">
                                                Geral
                                            </button>
                                        </div>
                                    </div>

                                    <input type="text" x-model="currentSubjectName" list="all_subjects_datalist"
                                           placeholder="Ex: Matemática (A), Programação Web..."
                                           class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-400 bg-white">
                                    <datalist id="all_subjects_datalist">
                                        <template x-for="s in subjects" :key="'dl-'+s.id">
                                            <option :value="s.name"></option>
                                        </template>
                                    </datalist>
                                </div>

                                <!-- Curso Selecionado Badge -->
                                <template x-if="currentCourseName">
                                    <div class="rounded-lg bg-indigo-100/70 border border-indigo-200 p-2 text-xs flex items-center justify-between">
                                        <div class="flex items-center gap-1.5 text-indigo-900 font-semibold truncate">
                                            <span>🎓 Curso:</span>
                                            <span class="font-extrabold truncate" x-text="currentCourseName"></span>
                                        </div>
                                        <button type="button" @click="currentCourseId = ''; currentCourseName = ''" class="text-[10px] text-indigo-700 hover:underline">
                                            trocar
                                        </button>
                                    </div>
                                </template>

                                <!-- Turma/Série & Sala/Laboratório -->
                                <div class="grid grid-cols-2 gap-2.5">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Turma / Série</label>
                                        <input type="text" x-model="currentClassName"
                                               placeholder="Ex: 1º Info B, 2º Adm"
                                               class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-900 bg-white">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Sala / Laboratório</label>
                                        <input type="text" x-model="currentClassroom"
                                               placeholder="Ex: Lab 01, Sala 04"
                                               class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-900 bg-white">
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- SEÇÃO 2: CAMPOS PARA COORDENADOR --}}
                        <template x-if="currentScheduleType === 'coordination'">
                            <div class="bg-purple-50/60 p-3.5 rounded-2xl border border-purple-200 space-y-2">
                                <div class="flex items-center gap-2 text-purple-900 text-xs font-semibold">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Horário de Coordenação</span>
                                </div>
                                <p class="text-[11px] text-purple-700 leading-snug">
                                    Em horários de coordenação, o colaborador não possui disciplina vinculada.
                                </p>
                                <div>
                                    <label class="block text-[11px] font-semibold text-purple-900 uppercase mb-1">Descrição / Turno</label>
                                    <input type="text" x-model="currentShiftName"
                                           placeholder="Ex: Coordenação Pedagógica - Manhã"
                                           class="w-full rounded-xl border border-purple-300 px-3 py-2 text-xs text-gray-900 bg-white">
                                </div>
                            </div>
                        </template>

                        {{-- SEÇÃO 3: CAMPOS PARA FUNCIONÁRIO ADMINISTRATIVO --}}
                        <template x-if="currentScheduleType === 'administrative'">
                            <div class="bg-slate-50 p-3.5 rounded-2xl border border-slate-200 space-y-2">
                                <div class="flex items-center gap-2 text-slate-900 text-xs font-semibold">
                                    <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span>Expediente de Funcionário</span>
                                </div>
                                <p class="text-[11px] text-slate-600 leading-snug">
                                    Horário de jornada de trabalho administrativo do colaborador.
                                </p>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-900 uppercase mb-1">Setor / Identificação</label>
                                    <input type="text" x-model="currentShiftName"
                                           placeholder="Ex: Expediente Secretaria Acadêmica"
                                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-xs text-gray-900 bg-white">
                                </div>
                            </div>
                        </template>

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
                                    class="w-full rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white font-bold py-3 text-xs sm:text-sm shadow-md shadow-indigo-200 hover:shadow-lg transition flex items-center justify-center gap-2 cursor-pointer">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                <span>Adicionar à Grade Semanal</span>
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
                                    <h2 class="text-sm font-bold text-gray-900">Grade Semanal Configurada</h2>
                                    <p class="text-[11px] text-gray-500" x-text="selectedUserName ? 'Docente: ' + selectedUserName : 'Selecione um docente para visualizar'"></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="clearAllSlots()" class="rounded-xl border border-gray-200 px-3 py-1 text-[11px] font-semibold text-rose-600 hover:bg-rose-50 transition">
                                    Limpar Grade
                                </button>
                            </div>
                        </div>

                        <!-- Visualização da Grade Agrupada por Dias (Com Cores Exclusivas por Dia) -->
                        <div class="space-y-4">
                            <template x-for="day in [1, 2, 3, 4, 5, 6, 0]" :key="day">
                                <div class="rounded-2xl border-2 transition overflow-hidden"
                                     :style="getDaySlots(day).length > 0 ? ('border-color: ' + getDayColor(day).hex + '; background-color: ' + getDayColor(day).light_bg) : 'border-color: #e5e7eb; background-color: #fcfcfc; opacity: 0.85;'">

                                    <!-- Cabeçalho do Dia (Com Cor Sólida Temática e Alto Contraste) -->
                                    <div class="px-4 py-2.5 flex items-center justify-between text-white"
                                         :style="'background-color: ' + getDayColor(day).hex + ';'">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-white shadow-2xs"></span>
                                            <span class="text-xs font-extrabold uppercase tracking-wide text-white" x-text="getDayColor(day).short + ' • ' + daysList[day]"></span>
                                        </div>
                                        <span class="text-[10.5px] font-bold px-2.5 py-0.5 rounded-full bg-white/20 text-white shadow-2xs"
                                              x-text="getDaySlots(day).length + ' registro(s)'"></span>
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
                                                <div class="relative rounded-xl border bg-white p-3 shadow-2xs hover:shadow-xs transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2.5"
                                                     :style="'border-left-width: 5px; border-left-color: ' + getDayColor(day).hex + '; border-color: ' + (getDayColor(day).border_hex || getDayColor(day).hex) + '70;'">
                                                    <div class="flex items-center gap-3 min-w-0 flex-1">
                                                        <!-- Horário Badge com Cor do Dia -->
                                                        <div class="rounded-lg px-2.5 py-1 text-center flex-shrink-0 border shadow-2xs"
                                                             :style="'background-color: ' + getDayColor(day).light_bg + '; border-color: ' + (getDayColor(day).border_hex || getDayColor(day).hex) + '; color: ' + getDayColor(day).hex">
                                                            <div class="text-xs font-mono font-bold" x-text="slot.start_time + ' - ' + slot.end_time"></div>
                                                            <div class="text-[9.5px] font-semibold opacity-85" x-text="calculateDuration(slot.start_time, slot.end_time)"></div>
                                                        </div>

                                                        <!-- Detalhes do Horário / Disciplina / Turma / Curso -->
                                                        <div class="min-w-0 flex-1 space-y-1">
                                                            <template x-if="slot.schedule_type === 'coordination'">
                                                                <div>
                                                                    <span class="inline-flex items-center gap-1 rounded bg-purple-50 text-purple-700 border border-purple-200 px-1.5 py-0.5 text-[10.5px] font-bold">
                                                                        📋 Coordenação Pedagógica
                                                                    </span>
                                                                    <div class="text-[11px] text-gray-600 mt-0.5" x-text="slot.shift_name || ''"></div>
                                                                </div>
                                                            </template>

                                                            <template x-if="slot.schedule_type === 'administrative'">
                                                                <div>
                                                                    <span class="inline-flex items-center gap-1 rounded bg-slate-100 text-slate-700 border border-slate-200 px-1.5 py-0.5 text-[10.5px] font-bold">
                                                                        🏢 Expediente Administrativo
                                                                    </span>
                                                                    <div class="text-[11px] text-gray-600 mt-0.5" x-text="slot.shift_name || ''"></div>
                                                                </div>
                                                            </template>

                                                            <template x-if="slot.schedule_type === 'class' || !slot.schedule_type">
                                                                <div>
                                                                    <!-- Badge do Curso -->
                                                                    <template x-if="slot.course_name || slot.course_id">
                                                                        <div class="mb-1">
                                                                            <span class="inline-flex items-center gap-1 rounded-md bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-[10px] font-bold text-indigo-800">
                                                                                🎓 <span x-text="slot.course_name || getCourseName(slot.course_id)"></span>
                                                                            </span>
                                                                        </div>
                                                                    </template>

                                                                    <!-- Nome da Disciplina e Turma -->
                                                                    <div class="text-xs font-bold text-gray-900 flex items-center gap-1.5 flex-wrap">
                                                                        <span x-text="slot.subject_name || slot.shift_name || 'Aula'"></span>
                                                                        <template x-if="slot.division === 'A' || (slot.subject_name && (slot.subject_name.includes('(A)') || slot.subject_name.toUpperCase().includes('TURMA A')))">
                                                                            <span class="rounded bg-sky-100 text-sky-800 border border-sky-300 px-1.5 py-0.2 text-[9px] font-extrabold">Turma (A)</span>
                                                                        </template>
                                                                        <template x-if="slot.division === 'B' || (slot.subject_name && (slot.subject_name.includes('(B)') || slot.subject_name.toUpperCase().includes('TURMA B')))">
                                                                            <span class="rounded bg-orange-100 text-orange-800 border border-orange-300 px-1.5 py-0.2 text-[9px] font-extrabold">Turma (B)</span>
                                                                        </template>
                                                                    </div>

                                                                    <div class="flex items-center gap-1.5 mt-0.5 text-[10.5px]">
                                                                        <template x-if="slot.class_name">
                                                                            <span class="font-medium text-indigo-700 bg-indigo-50 px-1.5 py-0.2 rounded border border-indigo-100" x-text="slot.class_name"></span>
                                                                        </template>
                                                                        <template x-if="slot.classroom">
                                                                            <span class="text-gray-600 bg-gray-100 px-1.5 py-0.2 rounded font-medium" x-text="slot.classroom"></span>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                            </template>

                                                            <div class="flex flex-wrap items-center gap-1.5 mt-1 text-[10.5px] text-gray-500">
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
                                                            class="w-7 h-7 rounded-lg bg-gray-100 hover:bg-rose-100 text-gray-400 hover:text-rose-600 flex items-center justify-center transition flex-shrink-0 cursor-pointer"
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
                        <div class="rounded-2xl bg-indigo-900 text-white p-4 flex flex-wrap items-center justify-between gap-3 shadow-md">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-lg font-bold">
                                    ⏱️
                                </div>
                                <div>
                                    <div class="text-[11px] text-indigo-200 uppercase tracking-wider font-semibold">Carga Horária Total na Grade</div>
                                    <div class="text-sm sm:text-base font-black" x-text="totalHoursFormatted"></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 text-xs">
                                <div class="text-right">
                                    <div class="text-indigo-200 text-[10px] uppercase">Dias com Atividade</div>
                                    <div class="font-bold text-white" x-text="uniqueDaysCount + ' dia(s)'"></div>
                                </div>
                                <div class="text-right border-l border-indigo-800 pl-4">
                                    <div class="text-indigo-200 text-[10px] uppercase">Total de Horários</div>
                                    <div class="font-bold text-white" x-text="slots.length + ' registro(s)'"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão Salvar Grade Completa -->
                        <div class="pt-2 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl border border-indigo-700 bg-indigo-800/60 px-4 py-2.5 text-xs font-medium text-indigo-100 hover:bg-indigo-700 transition">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="rounded-xl bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white font-bold px-6 py-2.5 text-xs sm:text-sm shadow-md shadow-emerald-700/30 hover:shadow-lg transition flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Salvar Grade do Docente</span>
                            </button>
                        </div>

                    </div>

                </div>

            </div>
        </form>

    </div>

    @else
    {{-- ========================================================================= --}}
    {{-- MODO EDIÇÃO SIMPLES DE HORÁRIO ESPECÍFICO                                 --}}
    {{-- ========================================================================= --}}
    <div class="max-w-3xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-medium mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Painel</a>
                    <span>/</span>
                    <a href="{{ route('admin.work-schedules.index') }}" class="hover:text-indigo-600 transition">Grade de Horários</a>
                    <span>/</span>
                    <span class="text-gray-800 font-semibold">Editar Horário #{{ $schedule->id }}</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-gray-900 tracking-tight">
                    Editar Registro de Horário
                </h1>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.work-schedules.create', ['user_id' => $schedule->user_id]) }}" class="rounded-xl bg-indigo-50 border border-indigo-200 px-3.5 py-2 text-xs font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                    Grade Completa do Docente
                </a>
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-xl bg-white border border-gray-300 px-4 py-2 text-xs font-medium text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                    Voltar
                </a>
            </div>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl bg-rose-50 border border-rose-200 p-4 text-rose-800 text-xs font-medium space-y-1 shadow-2xs">
                <div class="font-bold">Atenção aos seguintes erros:</div>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.work-schedules.update', $schedule) }}"
              class="rounded-3xl border border-gray-200 bg-white p-6 shadow-xs space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Professor / Colaborador *</label>
                    <select name="user_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 bg-white font-semibold">
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id', $schedule->user_id) == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Unidade Escolar *</label>
                    <select name="unit_id" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 bg-white">
                        @foreach($units as $un)
                            <option value="{{ $un->id }}" {{ old('unit_id', $schedule->unit_id) == $un->id ? 'selected' : '' }}>
                                {{ $un->name }} ({{ $un->city }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Dia da Semana *</label>
                    <select name="day_of_week" required class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 bg-white font-semibold">
                        @foreach($daysList as $num => $dayName)
                            <option value="{{ $num }}" {{ old('day_of_week', $schedule->day_of_week) == $num ? 'selected' : '' }}>
                                {{ $dayName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Tipo de Atividade</label>
                    <select name="schedule_type" class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 bg-white font-semibold">
                        <option value="class" {{ old('schedule_type', $schedule->schedule_type) === 'class' ? 'selected' : '' }}>👨‍🏫 Aula com Disciplina</option>
                        <option value="coordination" {{ old('schedule_type', $schedule->schedule_type) === 'coordination' ? 'selected' : '' }}>📋 Coordenação Pedagógica</option>
                        <option value="administrative" {{ old('schedule_type', $schedule->schedule_type) === 'administrative' ? 'selected' : '' }}>🏢 Expediente Administrativo</option>
                    </select>
                </div>
            </div>

            <div class="p-4 rounded-2xl bg-indigo-50/50 border border-indigo-100 space-y-3">
                <div class="text-xs font-bold text-indigo-900 uppercase">Atribuição do Curso & Disciplina</div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Curso</label>
                        <select name="course_id" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs bg-white">
                            <option value="">-- Sem Curso Específico --</option>
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}" {{ old('course_id', $schedule->course_id) == $c->id ? 'selected' : '' }}>
                                    {{ $c->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Disciplina</label>
                        <input type="text" name="subject_name" value="{{ old('subject_name', $schedule->subject_name) }}"
                               placeholder="Ex: Matemática (A), Programação..."
                               class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs bg-white">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Turma / Série</label>
                        <input type="text" name="class_name" value="{{ old('class_name', $schedule->class_name) }}"
                               placeholder="Ex: 1º Info B"
                               class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs bg-white">
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Divisão Turma</label>
                        <select name="division" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs bg-white">
                            <option value="">Geral / Completa</option>
                            <option value="A" {{ old('division', $schedule->division) === 'A' ? 'selected' : '' }}>Turma (A)</option>
                            <option value="B" {{ old('division', $schedule->division) === 'B' ? 'selected' : '' }}>Turma (B)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Sala / Laboratório</label>
                        <input type="text" name="classroom" value="{{ old('classroom', $schedule->classroom) }}"
                               placeholder="Ex: Lab 01, Sala 04"
                               class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs bg-white">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Horário de Início *</label>
                    <input type="time" name="start_time" value="{{ old('start_time', substr($schedule->start_time, 0, 5)) }}" required
                           class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm font-mono font-bold text-gray-900 bg-white">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Horário de Término *</label>
                    <input type="time" name="end_time" value="{{ old('end_time', substr($schedule->end_time, 0, 5)) }}" required
                           class="w-full rounded-2xl border border-gray-300 px-3.5 py-2.5 text-xs sm:text-sm font-mono font-bold text-gray-900 bg-white">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Início Intervalo</label>
                    <input type="time" name="break_start_time" value="{{ old('break_start_time', $schedule->break_start_time ? substr($schedule->break_start_time, 0, 5) : '') }}" class="w-full rounded-xl border border-gray-300 px-2 py-1.5 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Fim Intervalo</label>
                    <input type="time" name="break_end_time" value="{{ old('break_end_time', $schedule->break_end_time ? substr($schedule->break_end_time, 0, 5) : '') }}" class="w-full rounded-xl border border-gray-300 px-2 py-1.5 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-[10px] font-semibold text-gray-600 uppercase mb-1">Tolerância (min)</label>
                    <input type="number" name="tolerance_minutes" value="{{ old('tolerance_minutes', $schedule->tolerance_minutes ?? 15) }}" min="0" max="60" class="w-full rounded-xl border border-gray-300 px-2 py-1.5 text-xs font-mono">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.work-schedules.index') }}" class="rounded-2xl border border-gray-300 px-5 py-2.5 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                    Cancelar
                </a>
                <button type="submit" class="rounded-2xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-6 py-2.5 text-xs sm:text-sm shadow-md shadow-indigo-200 transition">
                    Salvar Alterações
                </button>
            </div>
        </form>

    </div>
    @endif

</div>
@endsection