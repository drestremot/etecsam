<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etec Sebastiana Augusta de Moraes - Andradina</title>

    {{-- Aplica o modo escuro antes do primeiro paint, evitando flash de tela clara --}}
    <script>
        (function () {
            var stored = localStorage.getItem('etec_dark_mode');
            var dark = stored === '1' || (stored === null && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    {{-- Hamburger Menu Responsivo --}}
    <style>
        .mobile-hamburger { display: flex; align-items: center; gap: 0.75rem; }
        .mobile-nav-menu  { display: block; }
        @media (min-width: 1280px) {
            .mobile-hamburger { display: none !important; }
            .mobile-nav-menu  { display: none !important; }
        }
    </style>

    @php
        try {
            $activeTheme = \Illuminate\Support\Facades\Schema::hasTable('site_themes')
                ? \App\Models\SiteTheme::getActive()
                : null;
        } catch (\Exception $e) {
            $activeTheme = null;
        }
    @endphp

    @if($activeTheme)
    {{-- ═══════════════════════════════════════════════════════════════
         TEMA ATIVO: {{ $activeTheme->name }}
         CSS dinâmico que sobrepõe as cores etec-* quando o usuário
         não desativou o tema (classe etec-themed no <html>).
         As cores originais voltam removendo a classe via localStorage.
    ════════════════════════════════════════════════════════════════ --}}
    <style id="etec-theme-css">
        /* Backgrounds */
        html.etec-themed .bg-etec-dark  { background-color: {{ $activeTheme->primary_color }}   !important; }
        html.etec-themed .bg-etec-main  { background-color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .bg-etec-medium{ background-color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .bg-etec-accent{ background-color: {{ $activeTheme->accent_color }}    !important; }
        html.etec-themed .bg-etec-light { background-color: {{ $activeTheme->accent_color }}1a  !important; }
        /* Texts */
        html.etec-themed .text-etec-dark  { color: {{ $activeTheme->primary_color }}   !important; }
        html.etec-themed .text-etec-main  { color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .text-etec-medium{ color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .text-etec-accent{ color: {{ $activeTheme->accent_color }}    !important; }
        /* Borders */
        html.etec-themed .border-etec-dark  { border-color: {{ $activeTheme->primary_color }}   !important; }
        html.etec-themed .border-etec-main  { border-color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .border-etec-medium{ border-color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .border-etec-accent{ border-color: {{ $activeTheme->accent_color }}    !important; }
        /* Gradients */
        html.etec-themed .from-etec-dark  { --tw-gradient-from: {{ $activeTheme->primary_color }}   !important; }
        html.etec-themed .from-etec-main  { --tw-gradient-from: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .to-etec-main    { --tw-gradient-to:   {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .to-etec-medium  { --tw-gradient-to:   {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .to-etec-dark    { --tw-gradient-to:   {{ $activeTheme->primary_color }}   !important; }
        /* Hover states */
        html.etec-themed .hover\:bg-etec-main:hover   { background-color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .hover\:bg-etec-accent:hover { background-color: {{ $activeTheme->accent_color }}    !important; }
        html.etec-themed .hover\:text-etec-main:hover { color: {{ $activeTheme->secondary_color }} !important; }
        html.etec-themed .hover\:text-etec-accent:hover { color: {{ $activeTheme->accent_color }} !important; }
        /* Border accent on top bar */
        html.etec-themed .border-b-4.border-etec-accent { border-color: {{ $activeTheme->accent_color }} !important; }
        /* Ring */
        html.etec-themed .ring-etec-accent { --tw-ring-color: {{ $activeTheme->accent_color }} !important; }
        /* Faixa colorida no topo do site (indicador visual do tema) */
        html.etec-themed body::before {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg,
                {{ $activeTheme->primary_color }},
                {{ $activeTheme->secondary_color }},
                {{ $activeTheme->accent_color }},
                {{ $activeTheme->secondary_color }},
                {{ $activeTheme->primary_color }});
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
        }
    </style>

    {{-- Aplica a classe antes do paint para evitar flash --}}
    <script>
        (function () {
            var slug = '{{ $activeTheme->slug }}';
            var key  = 'etec_theme_off_' + slug;
            if (!localStorage.getItem(key)) {
                document.documentElement.classList.add('etec-themed');
            }
        })();
    </script>
    @endif
</head>

<body class="font-sans antialiased text-slate-100 bg-[#0b172a] flex flex-col min-h-screen selection:bg-amber-400 selection:text-slate-950" style="background-color: #0b172a; color: #f1f5f9;">

    {{-- Top bar institucional --}}
    <div class="bg-[#0b172a] text-slate-300 text-[11.5px] py-1.5 border-b border-white/10 transition-colors" style="background-color: #0b172a; color: #cbd5e1;">
        <div class="container mx-auto px-4 flex flex-wrap justify-between items-center gap-2">
            <div class="flex items-center gap-2 text-slate-300 font-medium">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Centro Paula Souza &bull; Governo do Estado de São Paulo</span>
            </div>
            <div class="flex items-center gap-4 text-slate-300">
                <a href="tel:1837026850" class="flex items-center gap-1.5 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                    <span>(18) 3702-6850</span>
                </a>
                <span class="text-white/20 hidden sm:inline">|</span>
                <a href="mailto:e028dir@cps.sp.gov.br" class="hidden sm:flex items-center gap-1.5 hover:text-white transition">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>e028dir@cps.sp.gov.br</span>
                </a>
                <span class="text-white/20 hidden md:inline">|</span>
                <a href="https://nsaetec.com.br/" target="_blank" rel="noopener noreferrer" class="hidden md:flex items-center gap-1 hover:text-amber-300 font-semibold transition">
                    <span>Portal NSA</span>
                </a>
                <span class="text-white/20">|</span>
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1 text-amber-300 hover:text-amber-200 font-bold transition">
                        <span>Minha Área</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-1 hover:text-white font-medium transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>Acesso Restrito</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>

    {{-- Header Sticky Glassmorphism --}}
    <header class="bg-[#0f223f] sticky top-0 z-50 border-b border-white/10 shadow-md transition-all text-white" style="background-color: #0f223f; color: #ffffff;" x-data="{ open: false, activeDrop: null }">
        <div class="container mx-auto px-4 py-2.5 flex justify-between items-center gap-4">

            {{-- Logotipo com proporção e efeito suave --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group flex-shrink-0">
                <img src="{{ asset('imagens/logo/etec_ra_aracatuba_andradina_sebastina_augusta_de_moraes/etec_ra_aracatuba_andradina_sebastina_augusta_de_moraes_br.png') }}"
                     onerror="this.onerror=null;this.src='{{ asset('imagens/logo/etec.png') }}'"
                     alt="Logo Etec Sebastiana Augusta de Moraes"
                     class="h-11 sm:h-13 w-auto transition-transform duration-300 group-hover:scale-105 drop-shadow-sm">
            </a>

            @unless(electoral_period_active())
            {{-- Menu Desktop --}}
            <nav class="hidden xl:flex items-center gap-1 text-sm font-medium whitespace-nowrap">
                <a href="{{ route('home') }}" class="px-3.5 py-2 text-white hover:text-amber-300 hover:bg-white/10 rounded-xl transition duration-200 {{ request()->routeIs('home') ? 'bg-white/10 text-amber-300 font-bold' : '' }}">
                    Início
                </a>
                <a href="{{ route('institutional') }}" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 {{ request()->routeIs('institutional') ? 'bg-white/10 text-white font-bold' : '' }}">
                    A Escola
                </a>
                <a href="{{ route('home') }}#unidades" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200">
                    Cursos & Polos
                </a>
                <a href="{{ route('home') }}#fazenda" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200">
                    Escola Fazenda
                </a>
                <a href="{{ route('library') }}" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 {{ request()->routeIs('library') ? 'bg-white/10 text-white font-bold' : '' }}">
                    Biblioteca
                </a>

                {{-- Dropdown Apoio Institucional --}}
                <div class="relative group" @mouseenter="activeDrop = 'apoio'" @mouseleave="activeDrop = null">
                    <button class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 flex items-center gap-1 select-none">
                        <span>Apoio</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute top-full left-0 pt-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 z-50">
                        <div class="bg-[#14284b]/98 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/15 p-2 min-w-[240px] space-y-1">
                            <a href="{{ route('cooperative') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Cooperativa Escola</div>
                                    <div class="text-[10px] text-white/50">Projetos práticos agrícolas</div>
                                </div>
                            </a>
                            <a href="{{ route('apm') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">APM</div>
                                    <div class="text-[10px] text-white/50">Associação Pais e Mestres</div>
                                </div>
                            </a>
                            <a href="{{ route('auxiliary-teachers') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-blue-400/20 text-blue-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Auxiliares Docentes</div>
                                    <div class="text-[10px] text-white/50">Apoio aos laboratórios</div>
                                </div>
                            </a>
                            <a href="{{ route('collaborators') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-purple-400/20 text-purple-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Colaboradores</div>
                                    <div class="text-[10px] text-white/50">Equipe administrativa</div>
                                </div>
                            </a>
                            <a href="{{ route('security-staff') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-rose-400/20 text-rose-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Vigilância & Segurança</div>
                                    <div class="text-[10px] text-white/50">Proteção do campus</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Dropdown Gestão --}}
                <div class="relative group" @mouseenter="activeDrop = 'gestao'" @mouseleave="activeDrop = null">
                    <button class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 flex items-center gap-1 select-none">
                        <span>Gestão</span>
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div class="absolute top-full left-0 pt-2 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-200 z-50">
                        <div class="bg-[#14284b]/98 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/15 p-2 min-w-[260px] space-y-1">
                            <a href="{{ route('superintendence') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-indigo-400/20 text-indigo-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Superintendência</div>
                                    <div class="text-[10px] text-white/50">Direção da Unidade</div>
                                </div>
                            </a>
                            <a href="{{ route('regional-supervision') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-teal-400/20 text-teal-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Supervisão Regional</div>
                                    <div class="text-[10px] text-white/50">Supervisão de Ensino PR01</div>
                                </div>
                            </a>
                            <a href="{{ route('academic') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-cyan-400/20 text-cyan-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Secretaria Acadêmica</div>
                                    <div class="text-[10px] text-white/50">Vida escolar e matrículas</div>
                                </div>
                            </a>
                            <a href="{{ route('academic-division') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-emerald-400/20 text-emerald-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Gestão Pedagógica</div>
                                    <div class="text-[10px] text-white/50">Coordenação de cursos</div>
                                </div>
                            </a>
                            <a href="{{ route('administrative') }}" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-white/85 hover:text-white hover:bg-white/10 rounded-xl transition">
                                <div class="w-8 h-8 rounded-lg bg-amber-400/20 text-amber-300 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <div class="font-bold text-white">Diretoria de Serviços</div>
                                    <div class="text-[10px] text-white/50">Administrativo e financeiro</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <a href="{{ route('contact') }}" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 {{ request()->routeIs('contact') ? 'bg-white/10 text-white font-bold' : '' }}">
                    Contato
                </a>
                <a href="{{ route('agenda') }}" class="px-3.5 py-2 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition duration-200 {{ request()->routeIs('agenda') ? 'bg-white/10 text-white font-bold' : '' }}">
                    Agenda
                </a>

                {{-- Botão Destaque Vestibulinho --}}
                <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer"
                   class="ml-2 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-bold text-xs rounded-xl shadow-md hover:from-amber-300 hover:to-amber-400 hover:shadow-lg transition duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>Vestibulinho</span>
                </a>

                {{-- Botão Alternador Tema Escuro/Claro --}}
                <button onclick="etecToggleDarkMode()" title="Alternar modo escuro/claro" aria-label="Alternar modo escuro"
                        class="ml-2 p-2 rounded-xl text-white/75 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12h1m13.435 6.364l-.707-.707M6.757 6.757l-.707-.707m11.314 0l-.707.707M6.757 17.243l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                {{-- Logo CPS --}}
                <div class="ml-3 pl-3 border-l border-white/15">
                    <a href="https://www.cps.sp.gov.br/" target="_blank" rel="noopener noreferrer" class="block bg-white px-2.5 py-1 rounded-xl shadow-xs hover:bg-slate-100 transition">
                        <img src="{{ asset('imagens/logo/logo-cps-2022.svg') }}" alt="Centro Paula Souza" class="h-8 w-auto">
                    </a>
                </div>
            </nav>
            @endunless

            {{-- Hambúrguer Mobile --}}
            @unless(electoral_period_active())
            <div class="mobile-hamburger flex items-center gap-2">
                <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer"
                   class="px-2.5 py-1.5 bg-amber-400 text-slate-950 font-bold text-[11px] rounded-lg shadow-xs">
                    Vestibulinho
                </a>

                <button onclick="etecToggleDarkMode()" title="Alternar modo escuro/claro" aria-label="Alternar modo escuro"
                        class="p-2 rounded-xl text-white/75 hover:text-white hover:bg-white/10 transition">
                    <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.485-8.485h1M3.515 12h1m13.435 6.364l-.707-.707M6.757 6.757l-.707-.707m11.314 0l-.707.707M6.757 17.243l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                <button @click="open = !open"
                    class="p-2 rounded-xl text-white hover:bg-white/10 transition focus:outline-none"
                    aria-label="Abrir menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" class="w-6 h-6 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            @endunless

        </div>

        {{-- Gaveta Mobile Drawer --}}
        @unless(electoral_period_active())
        <div x-show="open" x-transition.opacity class="mobile-nav-menu border-t border-white/10 bg-[#0f223f] shadow-2xl" style="display:none">
            <nav class="container mx-auto px-4 py-4 flex flex-col gap-1 text-sm font-medium">
                <a href="{{ route('home') }}" @click="open=false" class="px-4 py-2.5 text-white hover:text-amber-300 hover:bg-white/10 rounded-xl transition flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Início</span>
                </a>
                <a href="{{ route('institutional') }}" @click="open=false" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>A Escola</span>
                </a>
                <a href="{{ route('home') }}#unidades" @click="open=false" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>Cursos & Polos</span>
                </a>
                <a href="{{ route('home') }}#fazenda" @click="open=false" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                    <span>Escola Fazenda</span>
                </a>
                <a href="{{ route('library') }}" @click="open=false" class="px-4 py-2.5 text-white/80 hover:text-white hover:bg-white/10 rounded-xl transition flex items-center gap-2.5">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Biblioteca</span>
                </a>

                <div class="border-t border-white/10 pt-2 mt-2">
                    <span class="px-4 text-[10px] font-bold text-white/40 uppercase tracking-widest block mb-1">Apoio & Comunidade</span>
                    <div class="grid grid-cols-2 gap-1">
                        <a href="{{ route('cooperative') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span>Cooperativa</span>
                        </a>
                        <a href="{{ route('apm') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>APM</span>
                        </a>
                        <a href="{{ route('auxiliary-teachers') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            <span>Aux. Docentes</span>
                        </a>
                        <a href="{{ route('collaborators') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-400"></span>
                            <span>Colaboradores</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-2 mt-2">
                    <span class="px-4 text-[10px] font-bold text-white/40 uppercase tracking-widest block mb-1">Gestão & Secretaria</span>
                    <div class="grid grid-cols-2 gap-1">
                        <a href="{{ route('superintendence') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                            <span>Direção</span>
                        </a>
                        <a href="{{ route('academic') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                            <span>Secretaria</span>
                        </a>
                        <a href="{{ route('academic-division') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>Coordenação</span>
                        </a>
                        <a href="{{ route('administrative') }}" @click="open=false" class="px-3 py-2 text-xs text-white/80 hover:bg-white/10 rounded-lg transition flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            <span>Administrativo</span>
                        </a>
                    </div>
                </div>

                <div class="border-t border-white/10 pt-3 mt-2 flex flex-col gap-2">
                    <a href="{{ route('contact') }}" @click="open=false" class="px-4 py-2.5 text-white/80 hover:bg-white/10 rounded-xl transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>Fale Conosco</span>
                    </a>
                    <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer" class="px-4 py-2.5 bg-amber-400 text-slate-950 font-bold text-center rounded-xl shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        <span>Inscrições no Vestibulinho</span>
                    </a>
                </div>
            </nav>
        </div>
        @endunless
    </header>

    <main class="flex-grow bg-[#0b172a]" style="background-color: #0b172a;">
        @yield('content')
    </main>

    {{-- Footer Institucional 4 Colunas --}}
    <footer class="bg-[#0a1628] text-slate-300 text-sm border-t border-white/10 pt-16 pb-8 transition-colors" style="background-color: #0a1628; color: #cbd5e1;">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-12 border-b border-white/10">

                {{-- Coluna 1: Identidade Etec SAM & Redes --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('imagens/logo/etec_ra_aracatuba_andradina_sebastina_augusta_de_moraes/etec_ra_aracatuba_andradina_sebastina_augusta_de_moraes_br.png') }}"
                             onerror="this.onerror=null;this.src='{{ asset('imagens/logo/etec.png') }}'"
                             alt="Logo Etec" class="h-12 w-auto drop-shadow-sm">
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed font-normal">
                        A <strong class="text-white font-bold">Etec Sebastiana Augusta de Moraes</strong> é referência regional em educação técnica pública e gratuita, preparando jovens e adultos para o mercado de trabalho desde 1994.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="https://wa.me/551837026857" target="_blank" rel="noopener noreferrer" title="WhatsApp"
                           class="w-9 h-9 rounded-xl bg-white/10 hover:bg-emerald-500 hover:text-white flex items-center justify-center transition duration-200 text-slate-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-1.746-.873-2.892-1.557-4.043-3.53-.305-.524.305-.486.872-1.617.097-.198.05-.371-.05-.52-.099-.149-.67-1.612-.92-2.207-.241-.579-.487-.5-.67-.51-.173-.01-.371-.012-.57-.012-.198 0-.52.074-.793.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.04 3.115 4.946 4.247 2.906 1.131 2.906.755 3.428.707.521-.05 1.758-.718 2.006-1.413.248-.694.248-1.288.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12.004 2C6.486 2 2 6.486 2 12.004c0 1.937.557 3.78 1.611 5.39L2 22l4.751-1.572A9.953 9.953 0 0012.004 22C17.522 22 22 17.522 22 12.004 22 6.486 17.522 2 12.004 2zm0 18.012a8 8 0 01-4.273-1.238l-.306-.187-2.823.934.95-2.755-.2-.32A8.002 8.002 0 1112.004 20.012z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/etecsam" target="_blank" rel="noopener noreferrer" title="Instagram"
                           class="w-9 h-9 rounded-xl bg-white/10 hover:bg-rose-500 hover:text-white flex items-center justify-center transition duration-200 text-slate-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.332.014 7.052.072 2.695.272.273 2.69.073 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.332 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.668-.072-4.948-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/EtecSAM" target="_blank" rel="noopener noreferrer" title="Facebook"
                           class="w-9 h-9 rounded-xl bg-white/10 hover:bg-blue-600 hover:text-white flex items-center justify-center transition duration-200 text-slate-300">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12.07C22 6.51 17.52 2 12 2S2 6.51 2 12.07c0 5.02 3.66 9.18 8.44 9.93v-7.03H7.9v-2.9h2.54V9.85c0-2.51 1.49-3.9 3.78-3.9 1.1 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.9h-2.34V22c4.78-.75 8.44-4.91 8.44-9.93z"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Coluna 2: Cursos & Ensino --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-amber-400">Ensino & Cursos</h3>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('home') }}#unidades" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Cursos Técnicos e Integrados</a></li>
                        <li><a href="{{ route('home') }}#fazenda" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Escola Fazenda e Laboratórios</a></li>
                        <li><a href="{{ route('library') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Biblioteca e Acervo</a></li>
                        <li><a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer" class="text-amber-300 font-bold hover:underline inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Vestibulinho 2026</a></li>
                        <li><a href="https://nsaetec.com.br/" target="_blank" rel="noopener noreferrer" class="text-amber-300 font-bold hover:underline inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Portal do Aluno (NSA)</a></li>
                    </ul>
                </div>

                {{-- Coluna 3: Institucional & Governança --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-amber-400">Institucional</h3>
                    <ul class="space-y-2 text-xs text-slate-300">
                        <li><a href="{{ route('institutional') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Nossa História</a></li>
                        <li><a href="{{ route('superintendence') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Direção e Superintendência</a></li>
                        <li><a href="{{ route('academic') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Secretaria Acadêmica</a></li>
                        <li><a href="{{ route('apm') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Associação Pais e Mestres</a></li>
                        <li><a href="{{ route('cooperative') }}" class="hover:text-white hover:translate-x-1 transition duration-150 inline-flex items-center gap-1.5"><span class="text-amber-400 font-bold">&rsaquo;</span> Cooperativa Escola</a></li>
                    </ul>
                </div>

                {{-- Coluna 4: Localização e Atendimento --}}
                <div class="space-y-3">
                    <h3 class="text-xs font-extrabold uppercase tracking-widest text-amber-400">Localização & Contato</h3>
                    <ul class="space-y-3 text-xs text-slate-300">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="leading-relaxed">Estrada Vicinal Sebastião Lourenço da Silva, Km 11<br>Andradina/SP — CEP 16900-530</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <a href="tel:1837026850" class="hover:text-amber-300 font-semibold transition">(18) 3702-6850</a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:e028dir@cps.sp.gov.br" class="hover:text-amber-300 font-semibold transition">e028dir@cps.sp.gov.br</a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="pt-6 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-400">
                <span>&copy; {{ date('Y') }} Etec Sebastiana Augusta de Moraes &bull; Centro Paula Souza. Todos os direitos reservados.</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('contact') }}" class="hover:text-white transition">Fale Conosco</a>
                    <span>&bull;</span>
                    <a href="{{ route('admin.dashboard') }}" class="text-amber-400 hover:text-amber-300 font-bold transition">Painel Administrativo</a>
                </div>
            </div>
        </div>
    </footer>

    @if($activeTheme ?? null)
    {{-- ═══════════════════════════════════════════════════════
         POPUP DO TEMA — exibido uma vez por sessão
         Mostra a imagem e a descrição da campanha ativa.
    ════════════════════════════════════════════════════════ --}}
    <div id="etec-theme-popup"
         class="fixed inset-0 z-[999] flex items-center justify-center p-4"
         style="display:none !important; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px);">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden"
             style="max-height: 90vh; overflow-y: auto;">

            {{-- Cabeçalho colorido --}}
            <div class="relative h-32 flex items-end px-6 pb-4"
                 style="background: linear-gradient(135deg, {{ $activeTheme->primary_color }}, {{ $activeTheme->secondary_color }})">
                @if($activeTheme->popup_image)
                <img src="{{ photo_url($activeTheme->popup_image) }}" alt="{{ $activeTheme->name }}"
                     class="absolute inset-0 w-full h-full object-cover opacity-40">
                @endif
                <div class="relative">
                    <p class="text-white/80 text-xs font-bold uppercase tracking-widest mb-0.5">Campanha do Mês</p>
                    <h2 class="text-white text-2xl font-bold drop-shadow">{{ $activeTheme->name }}</h2>
                </div>
                <button onclick="etecClosePopup()"
                        class="absolute top-3 right-3 w-8 h-8 bg-black/20 hover:bg-black/40 rounded-full flex items-center justify-center text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Paleta de cores --}}
            <div class="flex gap-0 h-2">
                <div class="flex-1" style="background: {{ $activeTheme->primary_color }}"></div>
                <div class="flex-1" style="background: {{ $activeTheme->secondary_color }}"></div>
                <div class="flex-1" style="background: {{ $activeTheme->accent_color }}"></div>
            </div>

            {{-- Conteúdo --}}
            <div class="p-6">
                @if($activeTheme->description)
                <p class="text-gray-600 text-sm leading-relaxed">{{ $activeTheme->description }}</p>
                @else
                <p class="text-gray-500 text-sm italic">As cores do site estão adaptadas para esta data comemorativa.</p>
                @endif

                <div class="mt-6 flex flex-col sm:flex-row gap-3">
                    <button onclick="etecClosePopup()"
                            class="flex-1 py-2.5 px-4 rounded-lg text-white text-sm font-semibold transition hover:opacity-90"
                            style="background: {{ $activeTheme->primary_color }}">
                        Entendi, obrigado!
                    </button>
                    <button onclick="etecDisableTheme()"
                            class="flex-1 py-2.5 px-4 rounded-lg bg-gray-100 text-gray-600 text-sm font-medium hover:bg-gray-200 transition">
                        Preferir cores originais
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         BOTÃO FLUTUANTE — toggle tema on/off
         Fica visível no canto inferior direito quando há tema ativo.
    ════════════════════════════════════════════════════════ --}}
    <button id="etec-theme-toggle"
            onclick="etecToggleTheme()"
            title="Alternar cores da campanha"
            class="fixed bottom-6 right-6 z-[998] w-12 h-12 rounded-full shadow-lg flex items-center justify-center transition-all hover:scale-110 focus:outline-none"
            style="background: linear-gradient(135deg, {{ $activeTheme->primary_color }}, {{ $activeTheme->accent_color }})">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/>
        </svg>
        {{-- Tooltip --}}
        <span id="etec-toggle-tooltip"
              class="absolute bottom-14 right-0 bg-gray-800 text-white text-xs rounded-lg px-3 py-1.5 whitespace-nowrap opacity-0 pointer-events-none transition-opacity"
              style="min-width: 140px; text-align: center;">
            Clique para alternar cores
        </span>
    </button>

    <script>
    (function () {
        var SLUG       = '{{ $activeTheme->slug }}';
        var KEY_OFF    = 'etec_theme_off_'    + SLUG;
        var KEY_POPUP  = 'etec_theme_popup_'  + SLUG;
        var TODAY      = new Date().toDateString();

        // ── Popup ────────────────────────────────────────────────
        function showPopup() {
            var el = document.getElementById('etec-theme-popup');
            if (el) el.style.display = 'flex';
        }
        window.etecClosePopup = function () {
            var el = document.getElementById('etec-theme-popup');
            if (el) el.style.display = 'none';
            localStorage.setItem(KEY_POPUP, TODAY);
        };

        // Mostra popup se tema estiver ativo e usuário ainda não viu hoje
        if (!localStorage.getItem(KEY_OFF) && localStorage.getItem(KEY_POPUP) !== TODAY) {
            setTimeout(showPopup, 600);
        }

        // ── Toggle ───────────────────────────────────────────────
        function updateToggleBtn() {
            var btn     = document.getElementById('etec-theme-toggle');
            var tooltip = document.getElementById('etec-toggle-tooltip');
            var isOn    = document.documentElement.classList.contains('etec-themed');
            if (btn) {
                btn.style.opacity = isOn ? '1' : '0.45';
                btn.title = isOn ? 'Desativar cores da campanha' : 'Reativar cores da campanha';
            }
            if (tooltip) {
                tooltip.textContent = isOn ? 'Desativar cores do tema' : 'Reativar cores do tema';
            }
        }

        window.etecToggleTheme = function () {
            var html = document.documentElement;
            if (html.classList.contains('etec-themed')) {
                html.classList.remove('etec-themed');
                localStorage.setItem(KEY_OFF, '1');
            } else {
                html.classList.add('etec-themed');
                localStorage.removeItem(KEY_OFF);
            }
            updateToggleBtn();
        };

        window.etecDisableTheme = function () {
            document.documentElement.classList.remove('etec-themed');
            localStorage.setItem(KEY_OFF, '1');
            etecClosePopup();
            updateToggleBtn();
        };

        // Tooltip hover
        var btn = document.getElementById('etec-theme-toggle');
        if (btn) {
            btn.addEventListener('mouseenter', function () {
                document.getElementById('etec-toggle-tooltip').style.opacity = '1';
            });
            btn.addEventListener('mouseleave', function () {
                document.getElementById('etec-toggle-tooltip').style.opacity = '0';
            });
        }

        updateToggleBtn();
    })();
    </script>
    @endif

    {{-- Toggle de modo escuro/claro --}}
    <script>
        window.etecToggleDarkMode = function () {
            var isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('etec_dark_mode', isDark ? '1' : '0');
        };
    </script>

</body>
</html>
