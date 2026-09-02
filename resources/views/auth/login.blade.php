<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso Restrito — Painel Administrativo | Etec SAM</title>
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0b172a] text-white flex flex-col justify-between selection:bg-amber-400 selection:text-slate-950 font-sans antialiased relative overflow-x-hidden"
      style="background-color: #0b172a; color: #ffffff;">

    {{-- Efeito de Luz / Gradiente de Fundo --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 -right-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 left-1/3 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>

    {{-- Topbar mínima --}}
    <header class="relative z-10 w-full py-4 px-6 border-b border-white/10 bg-[#0c1b33]/80 backdrop-blur-md">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-300 hover:text-amber-300 transition group">
                <svg class="w-4 h-4 text-amber-400 group-hover:-translate-x-1 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Voltar ao Portal Institucional</span>
            </a>
            <div class="flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-[11px] font-semibold text-slate-300 hidden sm:inline">Ambiente Seguro &bull; Centro Paula Souza</span>
            </div>
        </div>
    </header>

    {{-- Conteúdo Central --}}
    <main class="relative z-10 flex-grow flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            {{-- Cartão de Login --}}
            <div class="bg-[#14284b] border border-white/15 rounded-3xl p-8 sm:p-10 shadow-2xl backdrop-blur-xl transition duration-300 hover:border-amber-400/30">

                {{-- Cabeçalho do Cartão com Logotipo Oficial em Branco --}}
                <div class="text-center mb-8 space-y-3">
                    <div class="flex items-center justify-center gap-3 mb-2">
                        <img src="{{ asset('imagens/logo/etec_ra_aracatuba_andradina_sebastina_augusta_de_moraes_br.png') }}"
                             alt="Etec Sebastiana Augusta de Moraes"
                             class="h-12 w-auto object-contain drop-shadow-md"
                             onerror="this.style.display='none'; document.getElementById('fallback-brand').style.display='block';">
                        <div id="fallback-brand" style="display:none;" class="text-2xl font-black tracking-tight text-white">
                            Etec <span class="text-amber-300">SAM</span>
                        </div>
                    </div>

                    <div>
                        <span class="inline-block text-[10.5px] font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3 py-1 rounded-full border border-amber-400/25 mb-2">
                            Painel Administrativo
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight">
                            Gestão Integrada
                        </h1>
                        <p class="text-xs text-slate-300 mt-1">
                            Informe suas credenciais institucionais para acessar o sistema.
                        </p>
                    </div>
                </div>

                {{-- Mensagem de Status / Sessão --}}
                @if (session('status'))
                    <div class="bg-amber-400/15 border border-amber-400/30 text-amber-200 text-xs rounded-2xl px-4 py-3 mb-6 flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Erros de Validação --}}
                @if ($errors->any())
                    <div class="bg-rose-950/60 border border-rose-500/50 text-rose-200 text-xs rounded-2xl px-4 py-3.5 mb-6 space-y-1">
                        <div class="flex items-center gap-2 font-bold text-rose-300">
                            <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Falha na autenticação</span>
                        </div>
                        <p class="text-slate-300 leading-relaxed">{{ $errors->first() }}</p>
                    </div>
                @endif

                {{-- Formulário de Login --}}
                <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPass: false }">
                    @csrf

                    {{-- Campo: E-mail --}}
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-200 uppercase tracking-wider mb-2">
                            E-mail Institucional
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="nome@etecsam.sp.gov.br"
                                   class="w-full bg-[#0b172a] border @error('email') border-rose-500 @else border-white/20 @enderror text-white placeholder-slate-400 text-xs sm:text-sm rounded-2xl pl-10 pr-4 py-3.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                        </div>
                    </div>

                    {{-- Campo: Senha --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-xs font-bold text-slate-200 uppercase tracking-wider">
                                Senha de Acesso
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-amber-300 hover:text-amber-200 hover:underline transition">
                                    Esqueceu a senha?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input :type="showPass ? 'text' : 'password'"
                                   id="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full bg-[#0b172a] border @error('password') border-rose-500 @else border-white/20 @enderror text-white placeholder-slate-400 text-xs sm:text-sm rounded-2xl pl-10 pr-11 py-3.5 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-amber-400 transition">
                            
                            {{-- Botão Ver Senha --}}
                            <button type="button"
                                    @click="showPass = !showPass"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-amber-300 focus:outline-none transition">
                                <svg x-show="!showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Manter conectado --}}
                    <div class="flex items-center justify-between pt-1">
                        <label for="remember_me" class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox"
                                   id="remember_me"
                                   name="remember"
                                   class="w-4 h-4 rounded-md bg-[#0b172a] border border-white/30 text-amber-400 focus:ring-amber-400 focus:ring-offset-0 focus:ring-1 cursor-pointer accent-amber-400">
                            <span class="text-xs text-slate-300 group-hover:text-white transition select-none">
                                Manter conectado
                            </span>
                        </label>
                    </div>

                    {{-- Botão de Submissão --}}
                    <div class="pt-2">
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-300 hover:to-amber-400 text-slate-950 font-black px-6 py-4 rounded-2xl shadow-xl hover:shadow-2xl hover:scale-[1.01] active:scale-[0.99] transition duration-200 uppercase tracking-wider text-xs sm:text-sm cursor-pointer">
                            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                            <span>Acessar Painel</span>
                        </button>
                    </div>
                </form>

            </div>

            {{-- Informações de Suporte e Autenticação --}}
            <div class="mt-6 text-center space-y-2">
                <p class="text-[11px] text-slate-400">
                    Problemas com seu acesso? Contate o suporte em
                    <a href="mailto:e028acad@cps.sp.gov.br" class="text-amber-300 hover:underline font-medium">e028acad@cps.sp.gov.br</a>
                </p>
            </div>

        </div>
    </main>

    {{-- Rodapé Institucional --}}
    <footer class="relative z-10 w-full py-4 text-center border-t border-white/10 bg-[#0c1b33]/60 backdrop-blur-md">
        <p class="text-[11px] text-slate-400">
            &copy; {{ date('Y') }} Etec Sebastiana Augusta de Moraes &bull; Centro Paula Souza &bull; Governo do Estado de São Paulo
        </p>
    </footer>

</body>
</html>
