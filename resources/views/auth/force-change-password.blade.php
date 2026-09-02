<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Primeiro Acesso — Alterar Senha | Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#dfe1e5] font-sans antialiased text-gray-900 min-h-screen flex flex-col justify-center items-center px-4 py-8">

    <div class="w-full max-w-md space-y-6">
        <!-- Logo & Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center p-3 bg-white rounded-2xl shadow-sm border border-gray-200">
                <img src="{{ asset('imagens/logo/etec.png') }}" alt="Etec SAM" class="h-12 w-auto">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Definir Nova Senha</h1>
            <p class="text-xs text-gray-600 font-medium">
                Olá, <strong>{{ auth()->user()->name }}</strong>! Por motivos de segurança no primeiro acesso, é necessário criar uma nova senha pessoal.
            </p>
        </div>

        @if(session('warning'))
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-xs font-bold text-amber-800 shadow-sm flex items-start gap-2.5">
                <span class="text-base flex-shrink-0"><svg class="w-4 h-4 inline-block text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg></span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-600 text-white p-4 text-xs font-bold shadow-md">
                <p class="font-semibold mb-1">Atenção aos seguintes pontos:</p>
                <ul class="list-disc list-inside space-y-0.5 font-medium">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Card Form -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-6 sm:p-8 space-y-5">
            <form action="{{ route('password.change.update') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Senha Atual (Temporária) *</label>
                    <input type="password" name="current_password" required placeholder="Digite sua senha temporária (ex: etec1234)"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('current_password') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nova Senha Pessoal *</label>
                    <input type="password" name="password" required placeholder="Mínimo 6 caracteres"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    @error('password') <p class="text-red-500 text-[11px] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Confirmar Nova Senha *</label>
                    <input type="password" name="password_confirmation" required placeholder="Repita exatamente a nova senha"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 text-xs sm:text-sm shadow-md transition active:scale-98">
                        Salvar Nova Senha & Acessar Sistema
                    </button>
                </div>
            </form>

            <div class="pt-2 border-t border-gray-100 text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-gray-500 hover:text-red-600 font-bold transition">
                        Encerrar sessão e sair
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>

