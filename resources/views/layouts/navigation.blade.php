<nav x-data="{ mobileOpen: false, activeDropdown: null }" class="bg-white border-b border-gray-200 shadow-2xs sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="w-full max-w-[1850px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center gap-6 lg:gap-8">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('imagens/logo/etec.png') }}" alt="Etec SAM" class="h-9 w-auto transform transition group-hover:scale-105">
                        <div class="flex flex-col">
                            <span class="font-bold text-gray-900 text-base tracking-tight leading-tight">Etec SAM</span>
                            <span class="text-[11px] font-semibold text-blue-600 tracking-wide">Gestão Integrada</span>
                        </div>
                    </a>
                </div>

                <!-- Reorganized Categorized Desktop Navigation -->
                <div class="hidden sm:flex items-center space-x-1 lg:space-x-1.5 sm:-my-px sm:ms-2">

                    {{-- 1. Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-3 py-2 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}">
                        <svg class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        <span>Dashboard</span>
                    </a>

                    {{-- 2. Operacional Dropdown --}}
                    @php
                        $isOperacionalActive = request()->routeIs('tasks.*') || request()->routeIs('medical-certificates.*') || request()->routeIs('legal-leaves.*') || request()->routeIs('van-reservations.*');
                    @endphp
                    <div class="relative" @click.outside="if (activeDropdown === 'operacional') activeDropdown = null">
                        <button
                            @click="activeDropdown = activeDropdown === 'operacional' ? null : 'operacional'"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-medium transition {{ $isOperacionalActive ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                        >
                            <svg class="w-4 h-4 {{ $isOperacionalActive ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            <span>Operacional</span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-gray-400" :class="activeDropdown === 'operacional' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div
                            x-show="activeDropdown === 'operacional'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 sm:w-72 rounded-2xl bg-white p-1.5 shadow-xl border border-gray-200/80 z-50 divide-y divide-gray-100 max-h-[85vh] overflow-y-auto"
                            style="display: none;"
                        >
                            <div class="py-0.5 space-y-0.5">
                                <a href="{{ route('tasks.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">KanbanTec</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Quadro de tarefas & OS</div>
                                    </div>
                                </a>

                                <a href="{{ route('medical-certificates.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Atestados Médicos</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Envio e homologação</div>
                                    </div>
                                </a>

                                <a href="{{ route('legal-leaves.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Folgas Previstas em Lei</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">TRE, Doação de Sangue, etc.</div>
                                    </div>
                                </a>

                                <a href="{{ route('van-reservations.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-rose-50 hover:text-rose-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-rose-100 text-rose-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Reserva de Van Escolar</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Viagens e visitas técnicas</div>
                                    </div>
                                </a>

                                <a href="{{ route('timeclock.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-purple-900 bg-purple-50/40 hover:bg-purple-100 hover:text-purple-950 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-200 text-purple-800 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-purple-950 text-xs truncate">Ponto Eletrônico</div>
                                        <div class="text-[10.5px] text-purple-700 font-normal truncate">Reconhecimento facial e GPS</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Laboratórios Dropdown --}}
                    @php
                        $isLabsActive = request()->routeIs('lab.reservations.*') || request()->routeIs('reservations.*');
                    @endphp
                    <div class="relative" @click.outside="if (activeDropdown === 'labs') activeDropdown = null">
                        <button
                            @click="activeDropdown = activeDropdown === 'labs' ? null : 'labs'"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-medium transition {{ $isLabsActive ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                        >
                            <svg class="w-4 h-4 {{ $isLabsActive ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <span>Laboratórios</span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-gray-400" :class="activeDropdown === 'labs' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div
                            x-show="activeDropdown === 'labs'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-64 sm:w-72 rounded-2xl bg-white p-1.5 shadow-xl border border-gray-200/80 z-50 divide-y divide-gray-100 max-h-[85vh] overflow-y-auto"
                            style="display: none;"
                        >
                            <div class="py-0.5 space-y-0.5">
                                <a href="{{ route('lab.reservations.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Quadro de Reservas</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Acompanhamento estilo Kanban</div>
                                    </div>
                                </a>

                                <a href="{{ route('lab.reservations.calendar') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Mapa Semanal</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Grade horária e ocupação</div>
                                    </div>
                                </a>

                                <a href="{{ route('lab.reservations.create') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Nova Reserva</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Agendar espaço e materiais</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- 4. Gerenciamento Dropdown (para Admin ou Coordenadores) --}}
                    @if(Auth::user()->is_admin || Auth::user()->hasRole('Coordenador'))
                    @php
                        $isGerenciamentoActive = request()->routeIs('admin.*') || request()->routeIs('lab.users.*') || request()->routeIs('lab.spaces.*') || request()->routeIs('lab.materials.*');
                    @endphp
                    <div class="relative" @click.outside="if (activeDropdown === 'gerenciamento') activeDropdown = null">
                        <button
                            @click="activeDropdown = activeDropdown === 'gerenciamento' ? null : 'gerenciamento'"
                            type="button"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs sm:text-sm font-medium transition {{ $isGerenciamentoActive ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100' }}"
                        >
                            <svg class="w-4 h-4 {{ $isGerenciamentoActive ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Gerenciamento</span>
                            <svg class="w-3.5 h-3.5 transform transition-transform duration-200 text-gray-400" :class="activeDropdown === 'gerenciamento' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>

                        <div
                            x-show="activeDropdown === 'gerenciamento'"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 mt-2 w-72 sm:w-80 rounded-2xl bg-white p-2 shadow-2xl border border-gray-200/80 z-50 divide-y divide-gray-100 max-h-[85vh] overflow-y-auto"
                            style="display: none;"
                        >
                            {{-- Hub Principal --}}
                            <div class="py-1 space-y-0.5">
                                <a href="{{ route('admin.dashboard') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Painel de Gerenciamento</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Hub central administrativo</div>
                                    </div>
                                </a>
                            </div>

                            {{-- Gestão Escolar & Docentes --}}
                            <div class="py-1.5 space-y-0.5">
                                <div class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Escola & Servidores</div>
                                
                                <a href="{{ route('lab.users.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Usuários & Colaboradores</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Acessos, perfis e equipe</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.work-schedules.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Grade de Horários</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Jornada e aulas dos docentes</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.timeclock.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-100 text-teal-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Radar de Ponto & Espelho</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Auditoria facial e frequência</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.courses.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Cursos & Departamentos</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Grade acadêmica e setores</div>
                                    </div>
                                </a>

                                <a href="{{ route('lab.spaces.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-teal-100 text-teal-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Espaços Didáticos (Labs)</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Ambientes e salas</div>
                                    </div>
                                </a>

                                <a href="{{ route('lab.materials.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-100 text-purple-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Inventário & Estoque</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Equipamentos e insumos</div>
                                    </div>
                                </a>
                            </div>

                            {{-- APM - Associação de Pais e Mestres --}}
                            <div class="py-1.5 space-y-0.5">
                                <div class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-indigo-600 flex items-center justify-between">
                                    <span>APM (Pais e Mestres)</span>
                                    <a href="{{ route('admin.apm-dashboard') }}" class="text-[9.5px] font-bold text-indigo-500 hover:text-indigo-800 lowercase">financeiro &rarr;</a>
                                </div>

                                <a href="{{ route('admin.apm-managers.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Gestores & Diretoria APM</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Cargos e diretoria executiva</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.apm-dashboard') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Financeiro & Prestação APM</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Entradas, saídas e balancete</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.apm-reports.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-100 text-purple-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Documentos & Estatutos APM</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Atas de reunião e prestações</div>
                                    </div>
                                </a>
                            </div>

                            {{-- Cooperativa Escola --}}
                            <div class="py-1.5 space-y-0.5">
                                <div class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-700 flex items-center justify-between">
                                    <span>Cooperativa Escola</span>
                                    <a href="{{ route('admin.cooperative-dashboard') }}" class="text-[9.5px] font-bold text-amber-600 hover:text-amber-800 lowercase">financeiro &rarr;</a>
                                </div>

                                <a href="{{ route('admin.cooperative-managers.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-700 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Gestores da Cooperativa</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Diretoria e conselho fiscal</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.cooperative-members.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Cooperados & Mensalidades</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Quadro de sócios e taxas</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.cooperative-dashboard') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-700 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Financeiro & Vendas da Fazenda</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Vendas agropecuárias e despesas</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.cooperative-housing-tenants.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Moradia Estudantil (Alojamento)</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Residentes e mensalidades</div>
                                    </div>
                                </a>

                                <a href="{{ route('admin.cooperative-reports.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-100 text-purple-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Documentos & Estatutos</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Atas e prestações de contas</div>
                                    </div>
                                </a>
                            </div>

                            {{-- Governança & Segurança --}}
                            <div class="py-1.5 space-y-0.5">
                                <div class="px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-gray-400">Governança & Acessos</div>

                                <a href="{{ route('admin.permissions.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-gray-700 hover:bg-indigo-50 hover:text-indigo-700 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-gray-900 text-xs truncate">Matriz de Permissões</div>
                                        <div class="text-[10.5px] text-gray-400 font-normal truncate">Controle de acessos e papéis</div>
                                    </div>
                                </a>

                                @if(Auth::user()->canViewSystemAudit())
                                <a href="{{ route('admin.audit.index') }}" @click="activeDropdown = null" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-xl text-xs font-medium text-purple-900 bg-purple-50/60 hover:bg-purple-100 hover:text-purple-950 transition">
                                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-purple-200 text-purple-800 text-xs flex-shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-semibold text-purple-950 text-xs truncate">Auditoria do Sistema</div>
                                        <div class="text-[10.5px] text-purple-700 font-normal truncate">Logs de acessos e edições</div>
                                    </div>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

            <!-- Right Actions: External Site & User Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2.5">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-2 rounded-xl transition font-medium">
                    <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Ver Site</span>
                </a>

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 focus:outline-none transition shadow-2xs">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-semibold shadow-2xs">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="max-w-[140px] truncate font-semibold text-gray-800 text-xs">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-1.5">
                                <svg class="fill-current h-3.5 w-3.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Route::has('lab.profile.edit'))
                        <x-dropdown-link :href="route('lab.profile.edit')">
                            {{ __('Meu Perfil') }}
                        </x-dropdown-link>
                        @endif

                        @if(Auth::user()->is_admin)
                        <x-dropdown-link :href="route('admin.dashboard')">
                            {{ __('Painel Administrativo') }}
                        </x-dropdown-link>
                        @endif

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-600 hover:text-red-800 font-medium">
                                {{ __('Sair / Encerrar Sessão') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Hamburger Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="mobileOpen = ! mobileOpen" class="inline-flex items-center justify-center p-2 rounded-xl text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileOpen, 'inline-flex': ! mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Mobile Menu with Accordion Subsections -->
    <div :class="{'block': mobileOpen, 'hidden': ! mobileOpen}" class="hidden sm:hidden bg-white border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1 px-3">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('Dashboard') }}</span>
                </span>
            </x-responsive-nav-link>

            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 px-3">Operacional</div>
            <x-responsive-nav-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    <span>KanbanTec</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('medical-certificates.index')" :active="request()->routeIs('medical-certificates.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ __('Atestados Médicos') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('legal-leaves.index')" :active="request()->routeIs('legal-leaves.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    <span>{{ __('Folgas Previstas em Lei') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('van-reservations.index')" :active="request()->routeIs('van-reservations.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    <span>{{ __('Reserva da Van Escolar') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('timeclock.index')" :active="request()->routeIs('timeclock.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Ponto Eletrônico (Facial)') }}</span>
                </span>
            </x-responsive-nav-link>

            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 px-3">Laboratórios</div>
            <x-responsive-nav-link :href="route('lab.reservations.index')" :active="request()->routeIs('lab.reservations.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                    <span>{{ __('Quadro de Reservas') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lab.reservations.calendar')" :active="request()->routeIs('lab.reservations.calendar')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Mapa Semanal</span>
                </span>
            </x-responsive-nav-link>
            
            @if(Auth::user()->is_admin || Auth::user()->hasRole('Coordenador'))
            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 px-3">Gerenciamento</div>
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ __('Painel de Gerenciamento') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.timeclock.index')" :active="request()->routeIs('admin.timeclock.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Radar de Ponto & Espelho') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.work-schedules.index')" :active="request()->routeIs('admin.work-schedules.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ __('Grade de Horários') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lab.users.index')" :active="request()->routeIs('lab.users.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>{{ __('Usuários & Colaboradores') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lab.spaces.index')" :active="request()->routeIs('lab.spaces.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ __('Espaços Didáticos (Labs)') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('lab.materials.index')" :active="request()->routeIs('lab.materials.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>{{ __('Inventário & Estoque') }}</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.courses.index')" :active="request()->routeIs('admin.courses.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>{{ __('Cursos & Departamentos') }}</span>
                </span>
            </x-responsive-nav-link>
            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-indigo-600 px-3 flex items-center justify-between">
                <span>APM (Pais e Mestres)</span>
                <a href="{{ route('admin.apm-dashboard') }}" class="text-[10px] font-bold text-indigo-500 lowercase">financeiro &rarr;</a>
            </div>
            <x-responsive-nav-link :href="route('admin.apm-managers.index')" :active="request()->routeIs('admin.apm-managers.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Gestores da APM</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.apm-dashboard')" :active="request()->routeIs('admin.apm-dashboard') || request()->routeIs('admin.apm-incomes.*') || request()->routeIs('admin.apm-expenses.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Financeiro & Balancete APM</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.apm-reports.index')" :active="request()->routeIs('admin.apm-reports.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Documentos & Atas APM</span>
                </span>
            </x-responsive-nav-link>

            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-amber-700 px-3 flex items-center justify-between">
                <span>Cooperativa Escola</span>
                <a href="{{ route('admin.cooperative-dashboard') }}" class="text-[10px] font-bold text-amber-600 lowercase">financeiro &rarr;</a>
            </div>
            <x-responsive-nav-link :href="route('admin.cooperative-managers.index')" :active="request()->routeIs('admin.cooperative-managers.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>Gestores da Cooperativa</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.cooperative-members.index')" :active="request()->routeIs('admin.cooperative-members.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Cooperados & Mensalidades</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.cooperative-dashboard')" :active="request()->routeIs('admin.cooperative-dashboard') || request()->routeIs('admin.cooperative-sales.*') || request()->routeIs('admin.cooperative-expenses.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Financeiro & Vendas da Fazenda</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.cooperative-housing-tenants.index')" :active="request()->routeIs('admin.cooperative-housing-tenants.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Moradia Estudantil (Alojamento)</span>
                </span>
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.cooperative-reports.index')" :active="request()->routeIs('admin.cooperative-reports.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Documentos da Cooperativa</span>
                </span>
            </x-responsive-nav-link>

            <div class="pt-2 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400 px-3">Governança & Acessos</div>
            <x-responsive-nav-link :href="route('admin.permissions.index')" :active="request()->routeIs('admin.permissions.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    <span>{{ __('Matriz de Permissões') }}</span>
                </span>
            </x-responsive-nav-link>
            @if(Auth::user()->canViewSystemAudit())
            <x-responsive-nav-link :href="route('admin.audit.index')" :active="request()->routeIs('admin.audit.*')">
                <span class="inline-flex items-center gap-2">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>{{ __('Auditoria do Sistema') }}</span>
                </span>
            </x-responsive-nav-link>
            @endif
            @endif

            <div class="pt-2">
                <x-responsive-nav-link :href="route('home')" target="_blank">
                    <span class="inline-flex items-center gap-2 text-emerald-700 font-semibold">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        <span>{{ __('Ver Site Institucional') }} &rarr;</span>
                    </span>
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-3 border-t border-gray-200 bg-gray-50 px-4">
            <div>
                <div class="font-semibold text-sm text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-normal text-xs text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @if(Route::has('lab.profile.edit'))
                <x-responsive-nav-link :href="route('lab.profile.edit')">
                    {{ __('Meu Perfil') }}
                </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-red-600 font-medium">
                        {{ __('Sair / Encerrar Sessão') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
