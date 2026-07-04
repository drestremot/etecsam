<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="px-5 py-4 border-b border-gray-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('imagens/logo/etec.png') }}" alt="Etec" class="h-10 w-auto">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-widest font-semibold">Painel Admin</p>
                    <p class="font-bold text-sm leading-tight text-white">Etec SAM</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-3 py-4 overflow-y-auto text-sm space-y-0.5">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            {{-- Pessoas --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Pessoas</p>

            <a href="{{ route('admin.teachers.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.teachers*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Professores / Funcionários</span>
            </a>

            {{-- Estrutura --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Estrutura</p>

            <a href="{{ route('admin.departments.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.departments*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <span>Departamentos</span>
            </a>

            <a href="{{ route('admin.units.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.units*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                </svg>
                <span>Unidades Escolares</span>
            </a>

            <a href="{{ route('admin.sectors.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.sectors*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
                <span>Setores / Escola Fazenda</span>
            </a>

            <a href="{{ route('admin.laboratories.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.laboratories*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6m-5 0v6L5 19a2 2 0 002 2h10a2 2 0 002-2L14 9V3M5 15h14"/>
                </svg>
                <span>Laboratórios</span>
            </a>

            {{-- Acadêmico --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Acadêmico</p>

            <a href="{{ route('admin.courses.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.courses*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
                <span>Cursos</span>
            </a>

            <a href="{{ route('admin.projects.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.projects*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                <span>Projetos</span>
            </a>

            {{-- Cooperativa Escola --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Cooperativa Escola</p>

            <a href="{{ route('admin.cooperative-managers.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-managers*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Gestores</span>
            </a>

            <a href="{{ route('admin.cooperative-members.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-members*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span>Cooperados</span>
            </a>

            <a href="{{ route('admin.cooperative-reports.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-reports*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Documentos (Estatuto, Atas...)</span>
            </a>

            <a href="{{ route('admin.cooperative-monthly-fees.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-monthly-fees*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-9c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mensalidades Cooperados</span>
            </a>

            {{-- Financeiro da Cooperativa --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Financeiro</p>

            <a href="{{ route('admin.cooperative-dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                <span>Dashboard Financeiro</span>
            </a>

            <a href="{{ route('admin.cooperative-expenses.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-expenses*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
                <span>Despesas</span>
            </a>

            <a href="{{ route('admin.cooperative-sales.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-sales*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-5l4-3 4 3M3 21h18"/>
                </svg>
                <span>Vendas</span>
            </a>

            {{-- Moradia Estudantil --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Moradia Estudantil</p>

            <a href="{{ route('admin.cooperative-housing-tenants.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-housing-tenants*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Moradores</span>
            </a>

            <a href="{{ route('admin.cooperative-housing-fees.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.cooperative-housing-fees*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-9c-1.11 0-2.08.402-2.599 1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Mensalidades Moradia</span>
            </a>

            {{-- APM --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">APM</p>

            <a href="{{ route('admin.apm-managers.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.apm-managers*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Gestão e Cargos</span>
            </a>

            <a href="{{ route('admin.apm-reports.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.apm-reports*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Documentos (Estatuto, Atas...)</span>
            </a>

            <a href="{{ route('admin.apm-dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.apm-dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
                </svg>
                <span>Dashboard Financeiro</span>
            </a>

            <a href="{{ route('admin.apm-expenses.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.apm-expenses*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m16 0a8 8 0 11-16 0 8 8 0 0116 0z"/>
                </svg>
                <span>Saídas</span>
            </a>

            <a href="{{ route('admin.apm-incomes.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.apm-incomes*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v8m-4-5l4-3 4 3M3 21h18"/>
                </svg>
                <span>Entradas</span>
            </a>

            {{-- Comunicação --}}
            <p class="px-3 pt-5 pb-1.5 text-[10px] font-bold text-gray-600 uppercase tracking-widest">Comunicação</p>

            <a href="{{ route('admin.events.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.events*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>Agenda / Eventos</span>
            </a>

            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.documents*') ? 'bg-indigo-600 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Documentos</span>
            </a>

                {{-- Carrossel da Página Inicial --}}
                <a href="{{ route('admin.home-slides.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.home-slides.*') ? 'bg-etec-main text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-main' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Carrossel da Página Inicial</span>
                </a>

                {{-- Parceiros --}}
                <a href="{{ route('admin.partners.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.partners.*') ? 'bg-etec-main text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-main' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span>Parceiros</span>
                </a>

                {{-- Temas --}}
                <a href="{{ route('admin.themes.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('admin.themes.*') ? 'bg-etec-main text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-main' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
                    </svg>
                    <span>Temas do Site</span>
                </a>

                {{-- Separador --}}
                <div class="my-2 border-t border-gray-100 dark:border-gray-700"></div>
                <p class="px-3 py-1 text-xs font-bold text-gray-400 uppercase tracking-widest">Laboratórios</p>

                <a href="{{ route('lab.dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('lab.dashboard') ? 'bg-etec-dark text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-dark' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('lab.reservations.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('lab.reservations.*') ? 'bg-etec-dark text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-dark' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Reservas</span>
                </a>

                <a href="{{ route('lab.spaces.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('lab.spaces.*') ? 'bg-etec-dark text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-dark' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span>Espaços Didáticos</span>
                </a>

                <a href="{{ route('lab.materials.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('lab.materials.*') ? 'bg-etec-dark text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-dark' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/>
                    </svg>
                    <span>Materiais</span>
                </a>

                <a href="{{ route('lab.users.index') }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('lab.users.*') ? 'bg-etec-dark text-white' : 'text-gray-600 hover:bg-gray-100 hover:text-etec-dark' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                    <span>Usuários</span>
                </a>
        </nav>

        <div class="px-4 py-4 border-t border-gray-800 bg-gray-950/40">
            <div class="flex items-center gap-2 mb-3">
                <div class="w-7 h-7 rounded-full bg-indigo-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->name }}</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('lab.profile.edit') }}" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Meu perfil
                </a>
                <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-1.5 text-xs text-gray-500 hover:text-gray-200 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Ver site
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="flex items-center gap-1.5 text-xs text-red-500 hover:text-red-300 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Conteúdo principal --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b border-gray-200 px-6 py-3.5 flex items-center justify-between">
            <h1 class="text-base font-semibold text-gray-800 tracking-tight">@yield('page-title', 'Painel')</h1>
            <div class="flex items-center gap-3">
                @yield('header-actions')
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-5 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                    <div class="flex items-center gap-2 font-semibold mb-2">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Por favor, corrija os erros abaixo:
                    </div>
                    <ul class="list-disc list-inside space-y-1 pl-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

</body>
</html>
