@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-24 sm:pb-10">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span>Painel de Indicadores</span>
                    <span class="rounded-xl bg-blue-50 border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-700">Visão Geral</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Resumo operacional integrado de atividades técnicas, ocupação de laboratórios, afastamentos, folgas e transporte
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    <span>Quadro KanbanTec</span>
                </a>
                <a href="{{ route('lab.reservations.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span>Reservas de Lab</span>
                </a>
                <a href="{{ route('lab.reservations.calendar') }}" class="inline-flex items-center gap-2 rounded-xl bg-teal-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-teal-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Mapa Semanal</span>
                </a>
                <a href="{{ route('medical-certificates.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-red-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-red-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span>Atestados</span>
                </a>
                <a href="{{ route('legal-leaves.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-purple-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-purple-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    <span>Folgas</span>
                </a>
                <a href="{{ route('van-reservations.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-sky-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-sky-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    <span>Van Escolar</span>
                </a>
            </div>
        </div>

        <!-- Section 1: Quick Action Launcher Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
            <a href="{{ route('tasks.create') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </span>
                    <span class="text-[11px] font-medium text-gray-400">Kanban</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-indigo-600 transition">Nova Atividade</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Criar demanda técnica</p>
                </div>
            </a>

            <a href="{{ route('lab.reservations.create') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </span>
                    <span class="text-[11px] font-medium text-gray-400">Reserva</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">Solicitar Lab</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Agendar aula prática</p>
                </div>
            </a>

            <a href="{{ route('medical-certificates.create') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-red-50 text-red-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    </span>
                    <span class="text-[11px] font-medium text-gray-400">Saúde</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-red-600 transition">Novo Atestado</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Anexar afastamento</p>
                </div>
            </a>

            <a href="{{ route('tasks.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" /></svg>
                    </span>
                    <span class="text-[11px] font-semibold text-gray-500">{{ $stats['total'] }} itens</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-amber-600 transition">Quadro Kanban</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Acompanhar demandas</p>
                </div>
            </a>

            <a href="{{ route('lab.reservations.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </span>
                    <span class="text-[11px] font-semibold text-gray-500">{{ $resStats['total'] ?? 0 }} aulas</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-emerald-600 transition">Quadro Reservas</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Fluxo de aulas e laudos</p>
                </div>
            </a>

            <a href="{{ route('van-reservations.index') }}" class="group flex flex-col justify-between rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs transition hover:-translate-y-0.5 hover:shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 group-hover:scale-105 transition font-semibold">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </span>
                    <span class="text-[11px] font-semibold text-sky-700">{{ $vanStats['pendentes'] ?? 0 }} pend.</span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-800 group-hover:text-sky-600 transition">Van Escolar</h3>
                    <p class="text-xs text-gray-500 mt-0.5">72h & Liberação</p>
                </div>
            </a>
        </div>

        <!-- Section 2: Indicadores KanbanTec (Demandas Técnicas) -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-blue-600"></span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Indicadores KanbanTec (Demandas Técnicas & Operacionais)
                    </h2>
                </div>
                <a href="{{ route('tasks.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                    Ver Quadro Completo &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-gray-800">
                    <span class="text-xs font-medium text-gray-500 block">Total Atividades</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 mt-1.5">{{ $stats['total'] }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-yellow-500">
                    <span class="text-xs font-medium text-gray-500 block">Atribuídas</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-yellow-700 mt-1.5">{{ $stats['atribuida'] }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-orange-500">
                    <span class="text-xs font-medium text-gray-500 block">Em Andamento</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-orange-700 mt-1.5">{{ $stats['em_andamento'] }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-emerald-500">
                    <span class="text-xs font-medium text-gray-500 block">Em Execução</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-700 mt-1.5">{{ $stats['em_execucao'] }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-red-500">
                    <span class="text-xs font-medium text-gray-500 block">Devolvidas</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-red-700 mt-1.5">{{ $stats['devolvida'] }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-sky-500">
                    <span class="text-xs font-medium text-gray-500 block">Concluídas</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-sky-700 mt-1.5">{{ $stats['concluida'] }}</div>
                </div>
            </div>
        </div>

        <!-- Section 3: Indicadores ReservasTec (Laboratórios & Aulas) -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs">
            <div class="flex items-center justify-between mb-4 border-b border-gray-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                    <h2 class="text-sm sm:text-base font-bold text-gray-800">
                        Indicadores ReservasTec (Ocupação de Laboratórios & Aulas)
                    </h2>
                </div>
                <a href="{{ route('lab.reservations.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800 hover:underline">
                    Ver Reservas &rarr;
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-blue-600">
                    <span class="text-xs font-medium text-gray-500 block">Ambientes / Labs</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-blue-800 mt-1.5">{{ $resStats['totalSpaces'] ?? 0 }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-purple-600">
                    <span class="text-xs font-medium text-gray-500 block">Itens Inventário</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-purple-800 mt-1.5">{{ $resStats['totalMaterials'] ?? 0 }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-amber-500">
                    <span class="text-xs font-medium text-gray-500 block">Solicitações Pendentes</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-amber-700 mt-1.5">{{ $resStats['pendentes'] ?? 0 }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-blue-500">
                    <span class="text-xs font-medium text-gray-500 block">Aprovadas / Prontas</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-blue-700 mt-1.5">{{ $resStats['aprovadas'] ?? 0 }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-emerald-500">
                    <span class="text-xs font-medium text-gray-500 block">Em Aula Agora</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-700 mt-1.5">{{ $resStats['em_aula'] ?? ($resStats['em_execucao'] ?? 0) }}</div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50/50 p-4 shadow-2xs border-t-4 border-t-purple-500">
                    <span class="text-xs font-medium text-gray-500 block">Aguard. Conferência</span>
                    <div class="text-2xl sm:text-3xl font-bold tracking-tight text-purple-700 mt-1.5">{{ $resStats['aguardando_conferencia'] ?? ($resStats['conferencia'] ?? 0) }}</div>
                </div>
            </div>
        </div>

        <!-- Section 4: Atestados, Folgas Legais e Van Escolar -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 sm:gap-6">
            <!-- Card Atestados -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2.5">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-red-50 text-red-600 inline-flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </span>
                        <span>Atestados Médicos</span>
                    </h3>
                    <a href="{{ route('medical-certificates.index') }}" class="text-xs font-semibold text-red-600 hover:underline">Ver</a>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 shadow-2xs">
                        <span class="text-xs text-gray-500 font-medium block">Total Registrado</span>
                        <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $certStats['total'] ?? 0 }}</span>
                    </div>
                    <div class="bg-amber-50/70 p-3 rounded-xl border border-amber-200 shadow-2xs">
                        <span class="text-xs text-amber-700 font-medium block">Aguardando Aval</span>
                        <span class="text-xl sm:text-2xl font-bold text-amber-800">{{ $certStats['pendentes'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Folgas Previstas em Lei -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2.5">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-purple-50 text-purple-600 inline-flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                        </span>
                        <span>Folgas Legais</span>
                    </h3>
                    <a href="{{ route('legal-leaves.index') }}" class="text-xs font-semibold text-purple-600 hover:underline">Ver</a>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 shadow-2xs">
                        <span class="text-xs text-gray-500 font-medium block">Dias Solicitados</span>
                        <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $legalLeaveStats['total_granted'] ?? 0 }}</span>
                    </div>
                    <div class="bg-emerald-50/70 p-3 rounded-xl border border-emerald-200 shadow-2xs">
                        <span class="text-xs text-emerald-700 font-medium block">Saldo em Dias</span>
                        <span class="text-xl sm:text-2xl font-bold text-emerald-800">{{ $legalLeaveStats['total_remaining'] ?? 0 }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Van Escolar -->
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-xs">
                <div class="flex items-center justify-between mb-3 border-b border-gray-100 pb-2.5">
                    <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                        <span class="p-1 rounded-lg bg-sky-50 text-sky-600 inline-flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </span>
                        <span>Van Escolar (72h)</span>
                    </h3>
                    <a href="{{ route('van-reservations.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Ver</a>
                </div>
                <div class="grid grid-cols-2 gap-3 mt-3">
                    <div class="bg-gray-50 p-3 rounded-xl border border-gray-200 shadow-2xs">
                        <span class="text-xs text-gray-500 font-medium block">Viagens Marcadas</span>
                        <span class="text-xl sm:text-2xl font-bold text-gray-900">{{ $vanStats['total'] ?? 0 }}</span>
                    </div>
                    <div class="bg-blue-50/70 p-3 rounded-xl border border-blue-200 shadow-2xs">
                        <span class="text-xs text-blue-700 font-medium block">Aguardando Direção</span>
                        <span class="text-xl sm:text-2xl font-bold text-blue-800">{{ $vanStats['pendentes'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
