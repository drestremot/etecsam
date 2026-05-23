<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0">
        <div class="px-6 py-5 border-b border-gray-700">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('imagens/logo/etec.png') }}" alt="Etec" class="h-10 w-auto">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-widest">Painel</p>
                    <p class="font-bold text-sm leading-tight">Etec SAM</p>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🏠</span> Dashboard
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Pessoas</p>

            <a href="{{ route('admin.teachers.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.teachers*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>👥</span> Professores / Funcionários
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Estrutura</p>

            <a href="{{ route('admin.departments.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.departments*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🏛️</span> Departamentos
            </a>
            <a href="{{ route('admin.units.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.units*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🏫</span> Unidades Escolares
            </a>
            <a href="{{ route('admin.sectors.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.sectors*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🚜</span> Setores / Escola Fazenda
            </a>
            <a href="{{ route('admin.laboratories.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.laboratories*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🧪</span> Laboratórios
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Acadêmico</p>

            <a href="{{ route('admin.courses.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.courses*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>📚</span> Cursos
            </a>
            <a href="{{ route('admin.projects.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.projects*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>🔬</span> Projetos
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-500 uppercase tracking-widest">Comunicação</p>

            <a href="{{ route('admin.events.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.events*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>📅</span> Agenda / Eventos
            </a>
            <a href="{{ route('admin.documents.index') }}"
               class="flex items-center gap-2 px-3 py-2 rounded-lg {{ request()->routeIs('admin.documents*') ? 'bg-indigo-600 text-white' : 'text-gray-300 hover:bg-gray-800' }}">
                <span>📄</span> Documentos
            </a>
        </nav>

        <div class="px-4 py-4 border-t border-gray-700">
            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->name }}</p>
            <div class="flex gap-3 mt-2">
                <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-white">Ver site</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300">Sair</button>
                </form>
            </div>
        </div>
    </aside>

    {{-- Conteúdo principal --}}
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
            <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Painel')</h1>
            <div class="flex items-center gap-4">
                @yield('header-actions')
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                    <p class="font-semibold mb-1">Por favor, corrija os erros abaixo:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
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
