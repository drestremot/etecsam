<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page-title', 'Gerenciamento') — Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
    @stack('styles')
</head>
<body class="bg-[#dfe1e5] font-sans antialiased text-gray-900">

    {{-- Topbar unificada --}}
    @include('layouts.navigation')

    {{-- Container Principal Amplo e Padronizado (KanbanTec Style) --}}
    <div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
        <div class="w-full max-w-[1850px] mx-auto space-y-6">

            <!-- Header da Página -->
            @hasSection('page-title')
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                        <span>@yield('page-title')</span>
                    </h1>
                    @hasSection('page-subtitle')
                        <p class="text-xs sm:text-sm text-gray-600 font-medium mt-1">@yield('page-subtitle')</p>
                    @endif
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @yield('header-actions')
                </div>
            </div>
            @endif

            <!-- Flash Alerts -->
            @if(session('success'))
                <div class="rounded-2xl bg-emerald-500 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                    <span>{{ session('success') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl bg-red-600 text-white p-4 text-sm font-bold shadow-md flex items-center justify-between">
                    <span>{{ session('error') }}</span>
                    <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
                </div>
            @endif

            @if($errors->any())
                <div class="rounded-2xl bg-red-600 text-white p-4 text-sm font-bold shadow-md">
                    <p class="font-semibold mb-1">Por favor, corrija os erros abaixo:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs font-medium">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Conteúdo da Página -->
            <main>
                @yield('content')
            </main>

        </div>
    </div>

    @stack('scripts')
</body>
</html>
