@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         editOpen: false,
         allDepts: {{ json_encode($departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()) }},
         allCourses: {{ json_encode($courses->map(fn($c) => ['id' => $c->id, 'name' => $c->title])->values()) }},
         editDeptInput: '',
         editCourseInput: '',
         editUser: {
             id: null,
             name: '',
             email: '',
             role: '',
             job_title: '',
             department_ids: [],
             course_ids: [],
             registration_number: '',
             phone: '',
             coordenador_ids: [],
             updateUrl: ''
         },
         openEdit(u, updateUrl) {
             this.editDeptInput = '';
             this.editCourseInput = '';
             this.editUser = {
                 id: u.id,
                 name: u.name || '',
                 email: u.email || '',
                 role: u.role || 'Professor',
                 job_title: u.job_title || '',
                 department_ids: Array.isArray(u.department_ids) ? u.department_ids.map(Number).filter(Boolean) : (u.department_id ? [Number(u.department_id)] : []),
                 course_ids: Array.isArray(u.course_ids) ? u.course_ids.map(Number).filter(Boolean) : (u.course_id ? [Number(u.course_id)] : []),
                 registration_number: u.registration_number || '',
                 phone: u.phone || '',
                 coordenador_ids: Array.isArray(u.coordenador_ids) ? u.coordenador_ids.map(Number) : [],
                 updateUrl: updateUrl
             };
             this.editOpen = true;
         },
         addEditDept() {
             if (!this.editDeptInput) return;
             const id = Number(this.editDeptInput);
             if (!this.editUser.department_ids.includes(id)) {
                 this.editUser.department_ids.push(id);
             }
             this.editDeptInput = '';
         },
         removeEditDept(id) {
             this.editUser.department_ids = this.editUser.department_ids.filter(x => x !== Number(id));
         },
         addEditCourse() {
             if (!this.editCourseInput) return;
             const id = Number(this.editCourseInput);
             if (!this.editUser.course_ids.includes(id)) {
                 this.editUser.course_ids.push(id);
             }
             this.editCourseInput = '';
         },
         removeEditCourse(id) {
             this.editUser.course_ids = this.editUser.course_ids.filter(x => x !== Number(id));
         },
         deptColor(id) {
             const colors = [
                 'bg-indigo-50 border-indigo-200 text-indigo-900',
                 'bg-emerald-50 border-emerald-200 text-emerald-900',
                 'bg-sky-50 border-sky-200 text-sky-900',
                 'bg-purple-50 border-purple-200 text-purple-900',
                 'bg-rose-50 border-rose-200 text-rose-900',
                 'bg-teal-50 border-teal-200 text-teal-900',
                 'bg-amber-50 border-amber-200 text-amber-900',
                 'bg-fuchsia-50 border-fuchsia-200 text-fuchsia-900',
             ];
             return colors[Math.abs(Number(id)) % colors.length];
         },
         courseColor(id) {
             const colors = [
                 'bg-amber-50 border-amber-200 text-amber-900',
                 'bg-cyan-50 border-cyan-200 text-cyan-900',
                 'bg-lime-50 border-lime-200 text-lime-900',
                 'bg-violet-50 border-violet-200 text-violet-900',
                 'bg-orange-50 border-orange-200 text-orange-900',
                 'bg-blue-50 border-blue-200 text-blue-900',
                 'bg-emerald-50 border-emerald-200 text-emerald-900',
                 'bg-pink-50 border-pink-200 text-pink-900',
             ];
             return colors[Math.abs(Number(id)) % colors.length];
         },
         getDeptName(id) {
             const d = this.allDepts.find(x => x.id === Number(id));
             return d ? d.name : 'Departamento #' + id;
         },
         getCourseName(id) {
             const c = this.allCourses.find(x => x.id === Number(id));
             return c ? c.name : 'Curso #' + id;
         }
     }">
    <div class="w-full max-w-[1850px] mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-bold">Controle de Usuários</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Usuários & Colaboradores</span>
                    <span class="rounded-xl bg-indigo-100 border border-indigo-200 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                        {{ count($users) }} cadastros
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Gestão unificada de acessos, papéis de permissão, docentes, equipe escolar e status de login
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <form action="{{ route('lab.users.sync-all') }}" method="POST" class="inline" onsubmit="return confirm('Deseja sincronizar/criar contas para professores que ainda não possuem login?')">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500 active:scale-95">
                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Sincronizar Docentes</span>
                        @if(($pendingTeachersCount ?? 0) > 0)
                            <span class="rounded-full bg-emerald-800 px-1.5 py-0.2 text-[10px] font-bold">{{ $pendingTeachersCount }}</span>
                        @endif
                    </button>
                </form>

                <a href="{{ route('admin.permissions.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-purple-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    <span>Permissões</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Hub</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-emerald-500 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="rounded-xl bg-red-600 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif
        @if($errors->any())
            <div class="rounded-xl bg-rose-600 text-white px-4 py-3 text-xs font-semibold shadow-sm space-y-1">
                <div class="flex items-center justify-between font-bold">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Não foi possível salvar as informações:
                    </span>
                    <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
                </div>
                <div class="space-y-0.5 pl-5 font-normal text-[11.5px]">
                    @foreach($errors->all() as $err)
                        <div>• {{ $err }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3">
            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Total de Usuários</span>
                <div class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 mt-0.5">{{ \App\Models\User::count() }}</div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Professores</span>
                <div class="text-xl sm:text-2xl font-bold tracking-tight text-indigo-700 mt-0.5">
                    {{ \App\Models\User::role('Professor')->count() }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Auxiliares Docentes</span>
                <div class="text-xl sm:text-2xl font-bold tracking-tight text-teal-700 mt-0.5">
                    {{ \App\Models\User::role('Auxiliar')->count() }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Coordenação & Direção</span>
                <div class="text-xl sm:text-2xl font-bold tracking-tight text-purple-700 mt-0.5">
                    {{ \App\Models\User::where('is_admin', true)->orWhereHas('roles', fn($q) => $q->whereIn('name', ['Superintendente', 'Diretor', 'Coordenador']))->count() }}
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-3.5 shadow-2xs">
                <span class="text-[11px] font-medium text-gray-500 block">Troca de Senha Pendente</span>
                <div class="text-xl sm:text-2xl font-bold tracking-tight text-amber-700 mt-0.5">
                    {{ \App\Models\User::where('must_change_password', true)->count() }}
                </div>
            </div>
        </div>

        <!-- Card: Cadastrar Novo Usuário / Colaborador -->
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs"
             x-data="{
                 open: false,
                 selectedDeptInput: '',
                 selectedCourseInput: '',
                 selectedDepts: [],
                 selectedCourses: [],
                 addDept() {
                     if (!this.selectedDeptInput) return;
                     const id = Number(this.selectedDeptInput);
                     if (!this.selectedDepts.includes(id)) {
                         this.selectedDepts.push(id);
                     }
                     this.selectedDeptInput = '';
                 },
                 removeDept(id) {
                     this.selectedDepts = this.selectedDepts.filter(x => x !== Number(id));
                 },
                 addCourse() {
                     if (!this.selectedCourseInput) return;
                     const id = Number(this.selectedCourseInput);
                     if (!this.selectedCourses.includes(id)) {
                         this.selectedCourses.push(id);
                     }
                     this.selectedCourseInput = '';
                 },
                 removeCourse(id) {
                     this.selectedCourses = this.selectedCourses.filter(x => x !== Number(id));
                 }
             }">
            <div class="flex items-center justify-between cursor-pointer select-none" @click="open = !open">
                <h2 class="text-xs sm:text-sm font-bold text-gray-800 flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 font-bold text-xs">
                        +
                    </span>
                    <span>Cadastrar Novo Usuário & Colaborador</span>
                </h2>
                <button type="button" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800" x-text="open ? 'Recolher formulário' : '+ Novo Cadastro'"></button>
            </div>

            <form x-show="open" action="{{ route('lab.users.store') }}" method="POST" class="mt-4 pt-4 border-t border-gray-100 space-y-3.5" style="display: none;">
                @csrf

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Nome Completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex: Maria da Silva"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        @error('name')<p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">E-mail Institucional *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="maria.silva@etecsam.sp.gov.br"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        @error('email')<p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Papel no Sistema *</label>
                        <select name="role" required class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">Selecione um papel...</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')<p class="text-red-500 text-[10px] mt-0.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Cargo / Especialidade (Site)</label>
                        <input type="text" name="job_title" value="{{ old('job_title') }}" placeholder="Ex: Professor de Informática"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Matrícula / Registro</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number') }}" placeholder="Ex: 12345"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Telefone / Ramal</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(19) 99999-9999"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Senha Inicial (opcional)</label>
                        <input type="password" name="password" placeholder="Padrão: etec1234"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Confirmar Senha</label>
                        <input type="password" name="password_confirmation" placeholder="Repita a senha"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    </div>

                    <!-- Departamentos / Setores (Combobox + Add Button + Cards com botão remover) -->
                    <div class="sm:col-span-2 lg:col-span-2 min-w-0 space-y-2">
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase">
                            Departamentos / Setores
                        </label>

                        <div class="flex items-center gap-2 w-full min-w-0">
                            <select x-model="selectedDeptInput"
                                    class="w-0 flex-1 min-w-0 max-w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition truncate">
                                <option value="">Selecione um departamento para adicionar...</option>
                                <template x-for="dept in allDepts" :key="'opt-d-'+dept.id">
                                    <option :value="dept.id" :disabled="selectedDepts.includes(dept.id)" x-text="dept.name + (selectedDepts.includes(dept.id) ? ' (já adicionado)' : '')"></option>
                                </template>
                            </select>
                            <button type="button"
                                    @click="addDept()"
                                    :disabled="!selectedDeptInput"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold shadow-xs transition cursor-pointer flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Adicionar</span>
                            </button>
                        </div>

                        <!-- Cards dos departamentos adicionados com cores e botão de remover -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 w-full min-w-0" x-show="selectedDepts.length > 0">
                            <template x-for="id in selectedDepts" :key="'card-d-'+id">
                                <div :class="deptColor(id)"
                                     class="relative flex items-center justify-between p-2.5 rounded-xl border shadow-2xs transition min-w-0 w-full overflow-hidden">
                                    <div class="flex items-center gap-2.5 min-w-0 pr-6">
                                        <div class="w-7 h-7 rounded-lg bg-white/80 border border-black/5 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div class="min-w-0 truncate">
                                            <p class="text-xs font-bold leading-tight truncate" x-text="getDeptName(id)"></p>
                                            <p class="text-[9.5px] opacity-75 font-medium">Departamento / Setor</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="removeDept(id)"
                                            class="absolute top-1.5 right-1.5 w-5 h-5 rounded-md bg-white/90 hover:bg-red-500 hover:text-white text-gray-400 border border-black/5 flex items-center justify-center text-xs font-bold transition shadow-2xs cursor-pointer"
                                            title="Remover vínculo">
                                        &times;
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedDepts.length === 0" class="text-[11px] text-gray-400 italic py-0.5">
                            Nenhum departamento vinculado.
                        </div>

                        <input type="hidden" name="department_ids_json" :value="JSON.stringify(selectedDepts)">
                        <template x-for="id in selectedDepts" :key="'new-d-in-'+id">
                            <input type="hidden" name="department_ids[]" :value="id">
                        </template>
                    </div>

                    <!-- Cursos Técnicos Vinculados (Combobox + Add Button + Cards com botão remover) -->
                    <div class="sm:col-span-2 lg:col-span-2 min-w-0 space-y-2">
                        <label class="block text-[11px] font-semibold text-gray-700 uppercase">
                            Cursos Técnicos Vinculados
                        </label>

                        <div class="flex items-center gap-2 w-full min-w-0">
                            <select x-model="selectedCourseInput"
                                    class="w-0 flex-1 min-w-0 max-w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition truncate">
                                <option value="">Selecione um curso para adicionar...</option>
                                <template x-for="course in allCourses" :key="'opt-c-'+course.id">
                                    <option :value="course.id" :disabled="selectedCourses.includes(course.id)" x-text="course.name + (selectedCourses.includes(course.id) ? ' (já adicionado)' : '')"></option>
                                </template>
                            </select>
                            <button type="button"
                                    @click="addCourse()"
                                    :disabled="!selectedCourseInput"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold shadow-xs transition cursor-pointer flex-shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Adicionar</span>
                            </button>
                        </div>

                        <!-- Cards dos cursos adicionados com cores e botão de remover -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 w-full min-w-0" x-show="selectedCourses.length > 0">
                            <template x-for="id in selectedCourses" :key="'card-c-'+id">
                                <div :class="courseColor(id)"
                                     class="relative flex items-center justify-between p-2.5 rounded-xl border shadow-2xs transition min-w-0 w-full overflow-hidden">
                                    <div class="flex items-center gap-2.5 min-w-0 pr-6">
                                        <div class="w-7 h-7 rounded-lg bg-white/80 border border-black/5 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                            <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                        </div>
                                        <div class="min-w-0 truncate">
                                            <p class="text-xs font-bold leading-tight truncate" x-text="getCourseName(id)"></p>
                                            <p class="text-[9.5px] opacity-75 font-medium">Curso Técnico</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            @click="removeCourse(id)"
                                            class="absolute top-1.5 right-1.5 w-5 h-5 rounded-md bg-white/90 hover:bg-red-500 hover:text-white text-gray-400 border border-black/5 flex items-center justify-center text-xs font-bold transition shadow-2xs cursor-pointer"
                                            title="Remover vínculo">
                                        &times;
                                    </button>
                                </div>
                            </template>
                        </div>
                        <div x-show="selectedCourses.length === 0" class="text-[11px] text-gray-400 italic py-0.5">
                            Nenhum curso vinculado.
                        </div>

                        <input type="hidden" name="course_ids_json" :value="JSON.stringify(selectedCourses)">
                        <template x-for="id in selectedCourses" :key="'new-c-in-'+id">
                            <input type="hidden" name="course_ids[]" :value="id">
                        </template>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-3 flex items-center gap-2 pt-1">
                        <input type="checkbox" name="show_on_site" id="show_on_site" value="1" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-3.5 w-3.5 cursor-pointer">
                        <label for="show_on_site" class="text-xs font-medium text-gray-700 cursor-pointer select-none">
                            Sincronizar e exibir no site institucional
                        </label>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-end gap-2.5 pt-1">
                        <button type="button" @click="open = false" class="px-3 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition cursor-pointer">
                            Salvar Usuário & Sincronizar
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Users Table Container with Live Search and Sorting -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xs overflow-hidden" x-data="adminTable()">
            <!-- Search and Per Page bar -->
            <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
                <div class="flex items-center gap-2.5 flex-1 min-w-[200px]">
                    <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input x-model="q" @input="search()" type="text" placeholder="Buscar por nome, e-mail institucional, papel, cargo..."
                           class="flex-1 text-xs border-0 outline-none bg-transparent text-gray-800 placeholder-gray-400">
                    <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">limpar</button>
                </div>
                @include('admin.partials.per-page-selector')
            </div>

            <!-- Bulk Action Bar -->
            <div x-show="selected.length > 0" x-cloak class="px-4 py-2.5 bg-indigo-50 border-b border-indigo-100 flex flex-wrap items-center justify-between gap-3 text-xs">
                <div class="flex items-center gap-2 text-indigo-900 font-medium">
                    <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-indigo-600 text-white text-[10px] font-bold" x-text="selected.length"></span>
                    <span>item(ns) selecionado(s)</span>
                </div>
                <div class="flex items-center gap-2">
                    <form action="{{ route('lab.users.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="activate">
                        <template x-for="id in selected" :key="'act-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Ativar
                        </button>
                    </form>
                    <form action="{{ route('lab.users.bulk-action') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="action" value="deactivate">
                        <template x-for="id in selected" :key="'deact-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Desativar
                        </button>
                    </form>
                    <form action="{{ route('lab.users.bulk-action') }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir os usuários selecionados?');">
                        @csrf
                        <input type="hidden" name="action" value="delete">
                        <template x-for="id in selected" :key="'del-'+id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>
                        <button type="submit" class="rounded-lg bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 font-medium shadow-2xs transition">
                            Excluir
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
                            <th @click="sort('nome')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[200px]">
                                Usuário & Cargo <span class="ml-1 text-gray-400" x-text="icon('nome')"></span>
                            </th>
                            <th @click="sort('email')" class="px-3.5 py-3 cursor-pointer hover:bg-gray-100 select-none min-w-[180px]">
                                E-mail Institucional <span class="ml-1 text-gray-400" x-text="icon('email')"></span>
                            </th>
                            <th class="px-3 py-3 min-w-[140px]">Papel no Sistema</th>
                            <th class="px-3 py-3 min-w-[180px]">Vínculos & Departamentos</th>
                            <th class="px-3 py-3 text-center min-w-[80px]">Status</th>
                            <th class="px-3.5 py-3 text-right min-w-[90px]">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $u)
                        @php
                            $userDepts = $u->departments->isNotEmpty() ? $u->departments : ($u->department ? collect([$u->department]) : collect());
                            $userCourses = $u->courses->isNotEmpty() ? $u->courses : ($u->course ? collect([$u->course]) : collect());

                            $userDeptIds = $u->departments->pluck('id')->all();
                            if (empty($userDeptIds) && $u->department_id) {
                                $userDeptIds = [(int)$u->department_id];
                            }

                            $userCourseIds = $u->courses->pluck('id')->all();
                            if (empty($userCourseIds) && $u->course_id) {
                                $userCourseIds = [(int)$u->course_id];
                            }
                        @endphp
                        <tr class="hover:bg-gray-50/80 transition {{ !$u->is_active ? 'opacity-60' : '' }}"
                            data-row="{{ strtolower($u->name . ' ' . $u->email . ' ' . ($u->role ?? '') . ' ' . ($u->roles->pluck('name')->implode(' ')) . ' ' . ($userDepts->pluck('name')->implode(' ')) . ' ' . ($userCourses->pluck('title')->implode(' '))) }}"
                            data-nome="{{ strtolower($u->name) }}"
                            data-email="{{ strtolower($u->email) }}">
                            <td class="px-3 py-2.5 text-center">
                                @if($u->id !== auth()->id())
                                    <input type="checkbox" value="{{ $u->id }}" x-model="selected" data-bulk-item class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4 cursor-pointer">
                                @endif
                            </td>
                            <td class="px-3.5 py-2.5">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-[11px] font-semibold flex-shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($u->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 truncate">
                                        <p class="font-semibold text-gray-900 truncate text-xs sm:text-[13px] leading-snug" title="{{ $u->name }}">{{ $u->name }}</p>
                                        <p class="text-[11px] text-gray-500 truncate font-normal leading-tight">{{ $u->role ?? ($u->roles->first()?->name ?? 'Colaborador') }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-2.5">
                                <span class="font-mono text-xs text-gray-600 truncate block max-w-[210px] xl:max-w-[260px]" title="{{ $u->email }}">{{ $u->email }}</span>
                            </td>
                            <td class="px-3 py-2.5">
                                <form action="{{ route('lab.users.role', $u) }}" method="POST" class="inline-block max-w-full">
                                    @csrf @method('PATCH')
                                    <select name="role" onchange="this.form.submit()"
                                            class="rounded-lg border border-gray-300 bg-white px-2 py-1 text-xs text-gray-800 shadow-2xs focus:outline-none focus:ring-1 focus:ring-indigo-400 max-w-full">
                                        @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ $u->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <noscript>
                                        <button class="rounded-lg bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold text-gray-700">OK</button>
                                    </noscript>
                                </form>
                            </td>
                            <td class="px-3 py-2.5">
                                <div class="flex flex-wrap gap-1 items-center max-w-[340px]">
                                    {{-- Departamentos --}}
                                    @foreach($userDepts as $d)
                                        <span class="inline-flex items-center gap-1 text-[10.5px] font-medium text-indigo-700 bg-indigo-50 border border-indigo-200/80 px-2 py-0.5 rounded-md" title="Departamento: {{ $d->name }}">
                                            <svg class="w-3.5 h-3.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            {{ $d->name }}
                                        </span>
                                    @endforeach

                                    {{-- Cursos --}}
                                    @foreach($userCourses as $c)
                                        <span class="inline-flex items-center gap-1 text-[10.5px] font-medium text-amber-800 bg-amber-50 border border-amber-200/80 px-2 py-0.5 rounded-md" title="Curso: {{ $c->title }}">
                                            <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                            {{ $c->title }}
                                        </span>
                                    @endforeach

                                    {{-- Auxiliar Coordenadores --}}
                                    @if($u->hasRole('Auxiliar') && $u->coordenadoresVinculados->isNotEmpty())
                                        <span class="inline-flex items-center gap-1 text-[10.5px] text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200" title="{{ $u->coordenadoresVinculados->pluck('name')->implode(', ') }}">
                                            {{ $u->coordenadoresVinculados->count() }} coord.
                                        </span>
                                    @endif

                                    @if($userDepts->isEmpty() && $userCourses->isEmpty() && (!$u->hasRole('Auxiliar') || $u->coordenadoresVinculados->isEmpty()))
                                        <span class="text-gray-400 text-xs italic">Nenhum</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-2.5 text-center">
                                <form action="{{ route('lab.users.status', $u) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="px-2.5 py-0.5 rounded-full text-[11px] font-medium transition shadow-2xs {{ $u->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}">
                                        {{ $u->is_active ? 'Ativo' : 'Inativo' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-3.5 py-2.5 text-right whitespace-nowrap">
                                <div class="flex justify-end gap-1.5 items-center">
                                    <!-- Botão Editar -->
                                    <button type="button"
                                            @click="openEdit({{ json_encode([
                                                'id' => $u->id,
                                                'name' => $u->name,
                                                'email' => $u->email,
                                                'role' => $u->roles->first()?->name ?? 'Professor',
                                                'job_title' => $u->role ?? '',
                                                'department_ids' => array_values($userDeptIds),
                                                'course_ids' => array_values($userCourseIds),
                                                'registration_number' => $u->registration_number ?? '',
                                                'phone' => $u->phone ?? '',
                                                'coordenador_ids' => $u->coordenadoresVinculados->pluck('id')->toArray(),
                                            ]) }}, '{{ route('lab.users.update', $u) }}')"
                                            class="rounded-lg bg-blue-50 p-1.5 text-blue-600 hover:bg-blue-100 hover:text-blue-800 transition shadow-2xs cursor-pointer"
                                            title="Editar dados do usuário">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>

                                    <!-- Link Reset Senha -->
                                    <form action="{{ route('lab.users.reset-link', $u) }}" method="POST" title="Enviar link de redefinição de senha">
                                        @csrf
                                        <button class="rounded-lg bg-gray-100 p-1.5 text-gray-600 hover:bg-gray-200 transition shadow-2xs cursor-pointer" title="Enviar link de redefinição de senha">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Botão Excluir -->
                                    @if($u->id !== auth()->id())
                                    <form action="{{ route('lab.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir o usuário {{ addslashes($u->name) }}?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-50 p-1.5 text-red-600 hover:bg-red-100 transition shadow-2xs cursor-pointer" title="Excluir usuário">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400 text-xs">Nenhum usuário cadastrado.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @include('admin.partials.pagination-footer')
        </div>

    </div>

    <!-- Modal de Edição de Usuário & Colaborador -->
    <div x-show="editOpen" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity" @click="editOpen = false"></div>
        <div class="flex min-h-full items-center justify-center p-4">
            <div class="relative w-full max-w-2xl rounded-2xl bg-white p-5 sm:p-6 shadow-2xl transition-all" @click.stop>
                <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 flex items-center gap-2">
                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </span>
                        <span>Editar Usuário & Colaborador</span>
                    </h3>
                    <button type="button" @click="editOpen = false" class="text-gray-400 hover:text-gray-600 text-lg font-bold cursor-pointer">&times;</button>
                </div>

                <form :action="editUser.updateUrl" method="POST" class="space-y-3.5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Nome Completo *</label>
                            <input type="text" name="name" x-model="editUser.name" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">E-mail Institucional *</label>
                            <input type="email" name="email" x-model="editUser.email" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Papel no Sistema *</label>
                            <select name="role" x-model="editUser.role" required class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Cargo / Especialidade (Site)</label>
                            <input type="text" name="job_title" x-model="editUser.job_title" placeholder="Ex: Professor de Informática" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Matrícula / Registro</label>
                            <input type="text" name="registration_number" x-model="editUser.registration_number" placeholder="Ex: 12345" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Telefone / Ramal</label>
                            <input type="text" name="phone" x-model="editUser.phone" placeholder="(19) 99999-9999" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs sm:text-sm text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        </div>

                        <!-- Departamentos / Setores (Combobox + Add Button + Cards com botão remover) -->
                        <div class="sm:col-span-2 min-w-0 space-y-2">
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase">
                                Departamentos / Setores
                            </label>

                            <div class="flex items-center gap-2 w-full min-w-0">
                                <select x-model="editDeptInput"
                                        class="w-0 flex-1 min-w-0 max-w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition truncate">
                                    <option value="">Selecione um departamento para adicionar...</option>
                                    <template x-for="dept in allDepts" :key="'edit-opt-d-'+dept.id">
                                        <option :value="dept.id" :disabled="editUser.department_ids.includes(dept.id)" x-text="dept.name + (editUser.department_ids.includes(dept.id) ? ' (já adicionado)' : '')"></option>
                                    </template>
                                </select>
                                <button type="button"
                                        @click="addEditDept()"
                                        :disabled="!editDeptInput"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold shadow-xs transition cursor-pointer flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Adicionar</span>
                                </button>
                            </div>

                            <!-- Cards dos departamentos adicionados com cores e botão de remover -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 w-full min-w-0" x-show="editUser.department_ids.length > 0">
                                <template x-for="id in editUser.department_ids" :key="'edit-card-d-'+id">
                                    <div :class="deptColor(id)"
                                         class="relative flex items-center justify-between p-2.5 rounded-xl border shadow-2xs transition min-w-0 w-full overflow-hidden">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-6">
                                            <div class="w-7 h-7 rounded-lg bg-white/80 border border-black/5 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            </div>
                                            <div class="min-w-0 truncate">
                                                <p class="text-xs font-bold leading-tight truncate" x-text="getDeptName(id)"></p>
                                                <p class="text-[9.5px] opacity-75 font-medium">Departamento / Setor</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                                @click="removeEditDept(id)"
                                                class="absolute top-1.5 right-1.5 w-5 h-5 rounded-md bg-white/90 hover:bg-red-500 hover:text-white text-gray-400 border border-black/5 flex items-center justify-center text-xs font-bold transition shadow-2xs cursor-pointer"
                                                title="Remover vínculo">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div x-show="editUser.department_ids.length === 0" class="text-[11px] text-gray-400 italic py-0.5">
                                Nenhum departamento vinculado.
                            </div>

                            <input type="hidden" name="department_ids_json" :value="JSON.stringify(editUser.department_ids)">
                            <template x-for="id in editUser.department_ids" :key="'edit-d-in-'+id">
                                <input type="hidden" name="department_ids[]" :value="id">
                            </template>
                        </div>

                        <!-- Cursos Técnicos Vinculados (Combobox + Add Button + Cards com botão remover) -->
                        <div class="sm:col-span-2 min-w-0 space-y-2">
                            <label class="block text-[11px] font-semibold text-gray-700 uppercase">
                                Cursos Técnicos Vinculados
                            </label>

                            <div class="flex items-center gap-2 w-full min-w-0">
                                <select x-model="editCourseInput"
                                        class="w-0 flex-1 min-w-0 max-w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3 py-2 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-amber-400 transition truncate">
                                    <option value="">Selecione um curso para adicionar...</option>
                                    <template x-for="course in allCourses" :key="'edit-opt-c-'+course.id">
                                        <option :value="course.id" :disabled="editUser.course_ids.includes(course.id)" x-text="course.name + (editUser.course_ids.includes(course.id) ? ' (já adicionado)' : '')"></option>
                                    </template>
                                </select>
                                <button type="button"
                                        @click="addEditCourse()"
                                        :disabled="!editCourseInput"
                                        class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 disabled:opacity-40 disabled:cursor-not-allowed text-white text-xs font-semibold shadow-xs transition cursor-pointer flex-shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    <span>Adicionar</span>
                                </button>
                            </div>

                            <!-- Cards dos cursos adicionados com cores e botão de remover -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1 w-full min-w-0" x-show="editUser.course_ids.length > 0">
                                <template x-for="id in editUser.course_ids" :key="'edit-card-c-'+id">
                                    <div :class="courseColor(id)"
                                         class="relative flex items-center justify-between p-2.5 rounded-xl border shadow-2xs transition min-w-0 w-full overflow-hidden">
                                        <div class="flex items-center gap-2.5 min-w-0 pr-6">
                                            <div class="w-7 h-7 rounded-lg bg-white/80 border border-black/5 flex items-center justify-center flex-shrink-0 shadow-2xs">
                                                <svg class="w-3.5 h-3.5 text-current" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                                            </div>
                                            <div class="min-w-0 truncate">
                                                <p class="text-xs font-bold leading-tight truncate" x-text="getCourseName(id)"></p>
                                                <p class="text-[9.5px] opacity-75 font-medium">Curso Técnico</p>
                                            </div>
                                        </div>
                                        <button type="button"
                                                @click="removeEditCourse(id)"
                                                class="absolute top-1.5 right-1.5 w-5 h-5 rounded-md bg-white/90 hover:bg-red-500 hover:text-white text-gray-400 border border-black/5 flex items-center justify-center text-xs font-bold transition shadow-2xs cursor-pointer"
                                                title="Remover vínculo">
                                            &times;
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <div x-show="editUser.course_ids.length === 0" class="text-[11px] text-gray-400 italic py-0.5">
                                Nenhum curso vinculado.
                            </div>

                            <input type="hidden" name="course_ids_json" :value="JSON.stringify(editUser.course_ids)">
                            <template x-for="id in editUser.course_ids" :key="'edit-c-in-'+id">
                                <input type="hidden" name="course_ids[]" :value="id">
                            </template>
                        </div>

                        <template x-if="editUser.role === 'Auxiliar'">
                            <div class="sm:col-span-2 pt-2 border-t border-gray-100">
                                <label class="block text-[11px] font-semibold text-gray-700 uppercase mb-1">Vínculos de Coordenação (para Auxiliar)</label>
                                <select name="coordenador_ids[]" multiple size="3" x-model="editUser.coordenador_ids" class="w-full rounded-xl border border-gray-300 px-3 py-2 text-xs text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                    @foreach($coordenadoresList as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <p class="text-[10.5px] text-gray-500 mt-1">Segure Ctrl / Cmd para selecionar múltiplos coordenadores.</p>
                            </div>
                        </template>

                        <div class="sm:col-span-2 pt-2 border-t border-gray-100">
                            <p class="text-[11px] font-semibold text-gray-500 uppercase mb-1.5">Alterar Senha de Acesso (opcional - deixe em branco para manter a atual)</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-medium text-gray-600 mb-0.5">Nova Senha</label>
                                    <input type="password" name="password" placeholder="Mínimo 6 caracteres" class="w-full rounded-xl border border-gray-300 px-3 py-1.5 text-xs text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-medium text-gray-600 mb-0.5">Confirmar Nova Senha</label>
                                    <input type="password" name="password_confirmation" placeholder="Repita a nova senha" class="w-full rounded-xl border border-gray-300 px-3 py-1.5 text-xs text-gray-800 focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-gray-100">
                        <button type="button" @click="editOpen = false" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-indigo-500 transition cursor-pointer">
                            Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
