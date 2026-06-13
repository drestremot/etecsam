<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('imagens/logo/etec.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etec Sebastiana Augusta de Moraes - Andradina</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Hamburger Menu Responsivo --}}
    <style>
        .mobile-hamburger { display: flex; align-items: center; gap: 0.75rem; }
        .mobile-nav-menu  { display: block; }
        @media (min-width: 1024px) {
            .mobile-hamburger { display: none !important; }
            .mobile-nav-menu  { display: none !important; }
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-700 bg-gray-50 flex flex-col min-h-screen">

    {{-- Top bar --}}
    <div class="bg-etec-dark text-white text-xs py-2 border-b border-white/10">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <span class="text-gray-300">Centro Paula Souza &mdash; Governo do Estado de São Paulo</span>
            <div class="flex items-center gap-4">
                <a href="tel:1837026850" class="flex items-center gap-1.5 text-gray-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                    (18) 3702-6850
                </a>
                <a href="mailto:e028dir@cps.sp.gov.br" class="flex items-center gap-1.5 text-gray-300 hover:text-white transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    e028dir@cps.sp.gov.br
                </a>
            </div>
        </div>
    </div>

    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-gray-100" x-data="{ open: false }">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center group">
                <img src="{{ asset('imagens/logo/etec.png') }}" alt="Logo Etec Sebastiana Augusta de Moraes"
                    class="h-14 w-auto">
            </a>

            <nav class="hidden lg:flex items-center gap-0.5 text-sm font-medium">
                <a href="{{ route('home') }}" class="px-4 py-2 text-etec-dark hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Início</a>
                <a href="{{ route('institutional') }}" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">A Escola</a>
                <a href="{{ route('library') }}" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Biblioteca</a>
                <a href="{{ route('home') }}#unidades" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Cursos</a>
                <a href="{{ route('home') }}#fazenda" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Escola Fazenda</a>
                <a href="{{ route('academic') }}" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Secretaria</a>

                {{-- Dropdown Gestão --}}
                <div class="relative group">
                    <button class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition flex items-center gap-1 select-none">
                        Gestão
                        <svg class="w-3.5 h-3.5 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div class="absolute top-full left-0 pt-1 invisible opacity-0 group-hover:visible group-hover:opacity-100 transition-all duration-150 z-50">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 py-1.5 min-w-[220px] overflow-hidden">
                            <a href="{{ route('superintendence') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-etec-main hover:bg-gray-50 transition">
                                <div class="w-7 h-7 bg-etec-light rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong class="block font-semibold text-gray-800 text-xs">Superintendência</strong>
                                    <span class="text-xs text-gray-400">Direção da Unidade</span>
                                </div>
                            </a>
                            <a href="{{ route('academic-division') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-etec-main hover:bg-gray-50 transition">
                                <div class="w-7 h-7 bg-etec-light rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong class="block font-semibold text-gray-800 text-xs">Diretoria Acadêmica</strong>
                                    <span class="text-xs text-gray-400">Coordenação Pedagógica</span>
                                </div>
                            </a>
                            <a href="{{ route('administrative') }}"
                               class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:text-etec-main hover:bg-gray-50 transition">
                                <div class="w-7 h-7 bg-etec-light rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3.5 h-3.5 text-etec-main" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <strong class="block font-semibold text-gray-800 text-xs">Diretoria de Serviços</strong>
                                    <span class="text-xs text-gray-400">Administrativo e Financeiro</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <a href="{{ route('contact') }}" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Contato</a>
                <a href="{{ route('agenda') }}" class="px-4 py-2 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Agenda</a>
                <a href="#" class="ml-2 px-4 py-2 bg-etec-accent text-etec-dark rounded-lg hover:bg-yellow-400 transition font-semibold text-sm shadow-sm">Vestibulinho</a>
                <div class="ml-4 pl-4 border-l border-gray-200">
                    <img src="{{ asset('imagens/logo/logo-cps-2022.svg') }}" alt="Centro Paula Souza" class="h-10 w-auto opacity-80 hover:opacity-100 transition">
                </div>
            </nav>
            {{-- ↑ FIM DO MENU DESKTOP (hidden lg:flex) — NÃO REMOVER esta tag </nav> --}}

            {{-- ═══════════════════════════════════════════════════════════════
                 HAMBÚRGUER — visível APENAS em mobile (< 1024px)
                 ATENÇÃO: este bloco deve ficar FORA da <nav> acima e DENTRO
                 do <div class="container ... flex justify-between">.
                 O CSS .mobile-hamburger esconde-o em desktop via media query.
                 NÃO mover este bloco para dentro da <nav class="hidden lg:flex">.
            ════════════════════════════════════════════════════════════════ --}}
            <div class="mobile-hamburger">
                <img src="{{ asset('imagens/logo/logo-cps-2022.svg') }}" alt="Centro Paula Souza" class="h-8 w-auto opacity-70">
                <button @click="open = !open"
                    class="p-2 rounded-lg text-gray-600 hover:text-etec-main hover:bg-gray-100 transition focus:outline-none"
                    aria-label="Abrir menu">
                    <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            {{-- FIM DO HAMBÚRGUER --}}

        </div>{{-- fim container flex justify-between --}}

        {{-- ═══════════════════════════════════════════════════════════════
             MENU MOBILE — abre/fecha via Alpine.js x-show="open"
             Atualizar SEMPRE que adicionar itens ao menu desktop acima.
             Inclui todos os itens + subpáginas de Gestão expandidas.
        ════════════════════════════════════════════════════════════════ --}}
        <div x-show="open" x-transition class="mobile-nav-menu border-t border-gray-100 bg-white shadow-lg" style="display:none">
            <nav class="container mx-auto px-4 py-3 flex flex-col gap-0.5 text-sm font-medium">
                <a href="{{ route('home') }}"         @click="open=false" class="px-4 py-3 text-etec-dark hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Início</a>
                <a href="{{ route('institutional') }}" @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">A Escola</a>
                <a href="{{ route('library') }}"       @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Biblioteca</a>
                <a href="{{ route('home') }}#unidades" @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Cursos</a>
                <a href="{{ route('home') }}#fazenda"  @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Escola Fazenda</a>
                <a href="{{ route('academic') }}"      @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Secretaria</a>

                {{-- Gestão (expandido no mobile) --}}
                <div class="border-t border-gray-100 pt-1 mt-1">
                    <p class="px-4 py-1.5 text-xs font-bold text-gray-400 uppercase tracking-widest">Gestão</p>
                    <a href="{{ route('superintendence') }}"   @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Superintendência
                    </a>
                    <a href="{{ route('academic-division') }}" @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        Diretoria Acadêmica
                    </a>
                    <a href="{{ route('administrative') }}"    @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition flex items-center gap-2">
                        <svg class="w-4 h-4 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        Diretoria de Serviços
                    </a>
                </div>

                <div class="border-t border-gray-100 pt-1 mt-1">
                    <a href="{{ route('contact') }}" @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Contato</a>
                    <a href="{{ route('agenda') }}"  @click="open=false" class="px-4 py-3 text-gray-600 hover:text-etec-main hover:bg-gray-50 rounded-lg transition">Agenda</a>
                </div>

                <div class="pt-2 pb-1">
                    <a href="#" @click="open=false" class="block px-4 py-3 bg-etec-accent text-etec-dark rounded-lg hover:bg-yellow-400 transition font-semibold text-center shadow-sm">Vestibulinho</a>
                </div>
            </nav>
        </div>
        {{-- FIM DO MENU MOBILE --}}

    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-etec-dark text-white pt-12 pb-6 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-10 pb-10 border-b border-white/10">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <img src="{{ asset('imagens/logo/etec.png') }}" alt="Logo" class="h-10 w-auto brightness-200 opacity-80">
                        <span class="font-bold text-lg leading-tight text-white/90">Etec Sebastiana<br>Augusta de Moraes</span>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Referência em ensino técnico agrícola, formando profissionais competentes para o agronegócio e a tecnologia desde 1994.
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-300 mb-4">Links Rápidos</h3>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-etec-accent transition flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Portal do Aluno (NSA)</a></li>
                        <li><a href="#" class="hover:text-etec-accent transition flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Calendário Escolar</a></li>
                        <li><a href="#" class="hover:text-etec-accent transition flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Transparência Pública</a></li>
                        <li><a href="{{ route('library') }}" class="hover:text-etec-accent transition flex items-center gap-2"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>Biblioteca</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-bold uppercase tracking-widest text-gray-300 mb-4">Localização e Contato</h3>
                    <ul class="space-y-3 text-sm text-gray-400">
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 mt-0.5 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>Estrada Vicinal Sebastião Lourenço da Silva, Km 5<br>Andradina/SP — CEP 16900-000</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 7V5z"/></svg>
                            <a href="tel:1837026850" class="hover:text-white transition">(18) 3702-6850</a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:e028dir@cps.sp.gov.br" class="hover:text-white transition">e028dir@cps.sp.gov.br</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="pt-6 flex flex-col md:flex-row justify-between items-center gap-3 text-xs text-gray-500">
                <span>&copy; {{ date('Y') }} Etec Sebastiana Augusta de Moraes &mdash; Centro Paula Souza</span>
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 transition">Acesso Administrativo</a>
            </div>
        </div>
    </footer>

</body>
</html>
