@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Painel de Gerenciamento</span>
                    <span class="rounded-xl bg-indigo-50 border border-indigo-200 px-3 py-1 text-xs font-semibold text-indigo-700">Hub Administrativo</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Central de cadastros, governança de acessos, infraestrutura de laboratórios e estrutura escolar
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Dashboard Operacional</span>
                </a>
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Ver Site Institucional</span>
                </a>
            </div>
        </div>

        <!-- Section 1: Pessoas, Usuários & Acessos -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Pessoas, Usuários & Acessos Unificados
                    </h2>
                </div>
                <a href="{{ route('lab.users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                    Gerenciar Contas & Papéis &rarr;
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('lab.users.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-5 shadow-2xs hover:bg-white hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">{{ \App\Models\User::count() }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Usuários & Colaboradores</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Gestão unificada de login e senhas</p>
                    </div>
                </a>

                <a href="{{ route('admin.permissions.index') }}" class="group rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5 shadow-2xs hover:bg-white hover:border-indigo-300 hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-indigo-900 leading-tight">{{ \Spatie\Permission\Models\Permission::count() }}</div>
                        <h3 class="text-sm font-semibold text-indigo-950 group-hover:text-indigo-600 transition">Matriz de Permissões</h3>
                        <p class="text-xs text-indigo-700 mt-0.5">Políticas de acesso por grupo</p>
                    </div>
                </a>

                <a href="{{ route('admin.teachers.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-5 shadow-2xs hover:bg-white hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-gray-900 leading-tight">{{ $stats['teachers'] }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-amber-600 transition">Docentes no Site</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Biografias e Lattes</p>
                    </div>
                </a>

                @if(Auth::user()->canViewSystemAudit())
                <a href="{{ route('admin.audit.index') }}" class="group rounded-2xl border border-purple-200 bg-purple-50/50 p-5 shadow-2xs hover:bg-white hover:border-purple-300 hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-purple-200 text-purple-800 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-purple-950 leading-tight">{{ \App\Models\AuditLog::count() }}</div>
                        <h3 class="text-sm font-semibold text-purple-950 group-hover:text-purple-700 transition">Auditoria do Sistema</h3>
                        <p class="text-xs text-purple-700 mt-0.5">Logs de acessos e edições</p>
                    </div>
                </a>
                @endif
            </div>
        </div>

        <!-- Section 2: Ponto Eletrônico & Gestão de Jornada -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Ponto Eletrônico & Gestão de Jornada de Trabalho
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.timeclock.index') }}" class="text-xs font-semibold text-teal-700 hover:underline">Radar do Ponto</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.timeclock.mirror') }}" class="text-xs font-semibold text-indigo-700 hover:underline">Espelho Mensal</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.work-schedules.index') }}" class="text-xs font-semibold text-blue-700 hover:underline">Grade de Horários</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.timeclock.index') }}" class="group rounded-2xl border border-teal-200 bg-teal-50/40 p-5 shadow-2xs hover:bg-white hover:border-teal-300 hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-teal-100 text-teal-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-teal-900 leading-tight">{{ \App\Models\TimeClockRecord::whereDate('recorded_at', today())->count() }}</div>
                        <h3 class="text-sm font-semibold text-teal-950 group-hover:text-teal-700 transition">Radar de Presença Hoje</h3>
                        <p class="text-xs text-teal-700 mt-0.5">Batidas faciais e geolocalização</p>
                    </div>
                </a>

                <a href="{{ route('admin.timeclock.mirror') }}" class="group rounded-2xl border border-indigo-200 bg-indigo-50/40 p-5 shadow-2xs hover:bg-white hover:border-indigo-300 hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-indigo-900 leading-tight">{{ \App\Models\User::where('is_active', true)->count() }}</div>
                        <h3 class="text-sm font-semibold text-indigo-950 group-hover:text-indigo-700 transition">Espelho de Ponto Mensal</h3>
                        <p class="text-xs text-indigo-700 mt-0.5">Saldo de horas e integrações</p>
                    </div>
                </a>

                <a href="{{ route('admin.work-schedules.index') }}" class="group rounded-2xl border border-blue-200 bg-blue-50/40 p-5 shadow-2xs hover:bg-white hover:border-blue-300 hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-blue-900 leading-tight">{{ \App\Models\WorkSchedule::where('is_active', true)->count() }}</div>
                        <h3 class="text-sm font-semibold text-blue-950 group-hover:text-blue-700 transition">Grade de Horários</h3>
                        <p class="text-xs text-blue-700 mt-0.5">Turnos de professores por escola</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Section 3: Infraestrutura, Laboratórios & Estoque -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Infraestrutura & Gestão de Laboratórios
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('lab.spaces.index') }}" class="text-xs font-semibold text-teal-700 hover:underline">Espaços</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('lab.materials.index') }}" class="text-xs font-semibold text-purple-700 hover:underline">Inventário</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('lab.spaces.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-5 shadow-2xs hover:bg-white hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-teal-100 text-teal-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-teal-800 leading-tight">{{ \App\Models\Space::count() }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-teal-600 transition">Espaços Didáticos</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Salas de aula prática, capacidade e auxiliares</p>
                    </div>
                </a>

                <a href="{{ route('lab.materials.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-5 shadow-2xs hover:bg-white hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-purple-100 text-purple-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-purple-800 leading-tight">{{ \App\Models\Material::count() }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition">Inventário de Materiais</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Estoque de insumos, equipamentos e patrimônio</p>
                    </div>
                </a>

                <a href="{{ route('admin.laboratories.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-5 shadow-2xs hover:bg-white hover:shadow-xs transition flex items-center gap-4">
                    <div class="w-11 h-11 bg-blue-100 text-blue-700 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold tracking-tight text-blue-800 leading-tight">{{ $stats['laboratories'] }}</div>
                        <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Catálogo de Labs no Site</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Páginas de apresentação institucional</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Section 4: Estrutura Acadêmica & Escolar -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Estrutura Acadêmica, Cursos & Departamentos
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.courses.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['courses'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-amber-600 transition">Cursos Técnicos</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Grade e matriz curricular</p>
                </a>

                <a href="{{ route('admin.departments.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['departments'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition">Departamentos</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Diretoria e coordenações</p>
                </a>

                <a href="{{ route('admin.units.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['units'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-emerald-600 transition">Unidades & Extensões</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Prédios e polos</p>
                </a>

                <a href="{{ route('admin.sectors.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-orange-100 text-orange-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['sectors'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-orange-600 transition">Setores Internos</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Secretaria, TI, etc.</p>
                </a>
            </div>
        </div>

        <!-- Section 5: APM (Associação de Pais e Mestres) -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-gray-800">
                            APM — Associação de Pais e Mestres
                        </h2>
                        <p class="text-[11px] text-gray-500 font-normal">Gestão da diretoria, finanças, contribuições voluntárias e transparência</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.apm-dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">Painel Financeiro APM</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.apm-managers.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">Gestores</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.apm-reports.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 hover:underline">Documentos</a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <a href="{{ route('admin.apm-managers.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['apm_managers'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Gestores da APM</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Diretoria executiva e conselho</p>
                </a>

                <a href="{{ route('admin.apm-dashboard') }}" class="group rounded-2xl border border-indigo-200 bg-indigo-50/40 p-4 shadow-2xs hover:bg-white hover:border-indigo-300 hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </span>
                        <span class="text-xs font-bold text-indigo-700 bg-indigo-100 px-2 py-0.5 rounded-md">Transparência</span>
                    </div>
                    <h3 class="text-sm font-semibold text-indigo-950 group-hover:text-indigo-600 transition">Painel Financeiro</h3>
                    <p class="text-xs text-indigo-700 mt-0.5">Balancete e demonstrativo</p>
                </a>

                <a href="{{ route('admin.apm-incomes.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['apm_incomes'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-emerald-600 transition">Receitas / Entradas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Contribuições e doações</p>
                </a>

                <a href="{{ route('admin.apm-expenses.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['apm_expenses'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-rose-600 transition">Despesas / Saídas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Compras e manutenções</p>
                </a>

                <a href="{{ route('admin.apm-reports.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['apm_reports'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition">Documentos & Atas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Estatuto e prestações de contas</p>
                </a>
            </div>
        </div>

        <!-- Section 6: Cooperativa Escola & Moradia Estudantil -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-gray-800">
                            Cooperativa Escola & Moradia Estudantil
                        </h2>
                        <p class="text-[11px] text-gray-500 font-normal">Quadro de cooperados, produção agropecuária, vendas, alojamento e transparência</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.cooperative-dashboard') }}" class="text-xs font-semibold text-amber-700 hover:text-amber-900 hover:underline">Financeiro Cooperativa</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.cooperative-members.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">Cooperados</a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('admin.cooperative-housing-tenants.index') }}" class="text-xs font-semibold text-teal-700 hover:text-teal-900 hover:underline">Moradia</a>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="{{ route('admin.cooperative-managers.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['coop_managers'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Gestores</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Diretoria da Cooperativa</p>
                </a>

                <a href="{{ route('admin.cooperative-members.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['coop_members'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-emerald-600 transition">Cooperados</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sócios e mensalidades</p>
                </a>

                <a href="{{ route('admin.cooperative-dashboard') }}" class="group rounded-2xl border border-amber-200 bg-amber-50/40 p-4 shadow-2xs hover:bg-white hover:border-amber-300 hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        <span class="text-xs font-bold text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md">Financeiro</span>
                    </div>
                    <h3 class="text-sm font-semibold text-amber-950 group-hover:text-amber-700 transition">Painel Financeiro</h3>
                    <p class="text-xs text-amber-800 mt-0.5">Vendas e compras da fazenda</p>
                </a>

                <a href="{{ route('admin.cooperative-sales.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-teal-100 text-teal-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['coop_sales'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-teal-600 transition">Vendas / Produção</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Mudas, animais e produtos</p>
                </a>

                <a href="{{ route('admin.cooperative-housing-tenants.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['coop_tenants'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition">Moradia Estudantil</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Alojamento e residentes</p>
                </a>

                <a href="{{ route('admin.cooperative-reports.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['coop_reports'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-purple-600 transition">Documentos & Atas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Estatutos e assembleias</p>
                </a>
            </div>
        </div>

        <!-- Section 7: Conteúdo do Site Institucional -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    </span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Conteúdo Institucional do Portal
                    </h2>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <a href="{{ route('admin.projects.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['projects'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-amber-600 transition">Projetos & TCCs</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Galeria de projetos</p>
                </a>

                <a href="{{ route('admin.events.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ $stats['events'] }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-rose-600 transition">Eventos Escolares</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Feiras, palestras e semanas</p>
                </a>

                <a href="{{ route('admin.partners.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ \App\Models\Partner::count() }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Empresas Parceiras</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Estágios e parcerias</p>
                </a>

                <a href="{{ route('admin.documents.index') }}" class="group rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs hover:bg-white hover:shadow-xs transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span class="text-2xl font-bold tracking-tight text-gray-900">{{ \App\Models\Document::count() }}</span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-emerald-600 transition">Documentos & Manuais</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Downloads institucionais</p>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
