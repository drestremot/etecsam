@extends('layouts.app')

@section('content')

{{-- Page header --}}
<div class="border-b border-gray-200 dark:border-white/10">
    <div class="container mx-auto px-4 py-16 text-center">
        <span class="text-etec-medium dark:text-etec-accent font-bold uppercase tracking-widest text-xs">Institucional</span>
        <h1 class="text-4xl font-bold text-etec-dark dark:text-white mt-2 mb-4">Nossa História</h1>
        <div class="w-16 h-1 bg-etec-accent mx-auto"></div>
        <p class="mt-6 text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
            Conheça a trajetória da Etec Sebastiana Augusta de Moraes e nosso compromisso com o
            ensino agrícola de qualidade em Andradina e região.
        </p>
    </div>
</div>

<div class="pb-16">
    <div class="container mx-auto px-4">

        {{-- História --}}
        <div class="grid md:grid-cols-2 gap-12 items-center mb-24">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?q=80&w=1000&auto=format&fit=crop"
                     alt="Fachada da Escola" class="rounded-2xl shadow-xl w-full object-cover">
                <div class="absolute -bottom-4 -right-4 bg-etec-accent text-etec-dark font-bold text-sm px-5 py-3 rounded-xl shadow-lg">
                    Desde 1994
                </div>
            </div>

            <div class="space-y-5 text-gray-700 dark:text-gray-300 leading-relaxed">
                <div>
                    <h2 class="text-2xl font-bold text-etec-dark dark:text-white mb-3">
                        Tradição e Inovação no Campo
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">
                        Desde sua fundação, a Etec Sebastiana Augusta de Moraes tem sido pilar fundamental no
                        desenvolvimento da região de Andradina e do Oeste Paulista. Somos um solo fértil onde
                        germinam o conhecimento, a técnica e o futuro do agronegócio.
                    </p>
                </div>

                <div class="bg-etec-main border border-etec-dark/30 dark:border-white/10 rounded-xl p-5">
                    <h3 class="font-bold text-white mb-2">Raízes Fortes — Integração ao CPS</h3>
                    <p class="text-sm text-blue-100">
                        Em 1994, a escola foi oficialmente integrada ao Centro Paula Souza (CPS), alinhando
                        a vocação agrícola regional com a excelência educacional de uma das maiores instituições
                        de ensino técnico da América Latina.
                    </p>
                </div>

                <div class="bg-etec-main border border-etec-dark/30 dark:border-white/10 rounded-xl p-5">
                    <h3 class="font-bold text-white mb-2">O Diferencial "Escola-Fazenda"</h3>
                    <p class="text-sm text-blue-100">
                        Nossos alunos não apenas estudam o agronegócio — eles o vivenciam. Do manejo do solo
                        à colheita, passando pela pecuária e gestão, o estudante está imerso na rotina produtiva.
                        Somos referência em <strong class="text-white">Pedagogia da Alternância</strong> no estado de São Paulo.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-3 pt-2">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-etec-light dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-etec-dark dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-800 dark:text-white block">Inovação</strong>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Tecnologia integrada ao campo</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-etec-light dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-etec-dark dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-800 dark:text-white block">Sustentabilidade</strong>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Agroecologia e sementes crioulas</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-etec-light dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-etec-dark dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-800 dark:text-white block">Comunidade</strong>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Agricultura familiar e sucessão</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 bg-etec-light dark:bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-etec-dark dark:text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        <div>
                            <strong class="text-sm text-gray-800 dark:text-white block">Gratuidade</strong>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Ensino público de qualidade</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estrutura Administrativa --}}
        <div class="bg-white/50 dark:bg-white/5 rounded-2xl py-16 px-8 -mx-4 md:-mx-8 lg:-mx-0">
            <div class="text-center mb-14">
                <span class="text-etec-medium dark:text-etec-accent font-bold uppercase tracking-widest text-xs">Gestão</span>
                <h2 class="text-3xl font-bold text-etec-dark dark:text-white mt-2">
                    Estrutura Administrativa
                </h2>
                <div class="w-16 h-1 bg-etec-accent mx-auto mt-4"></div>
            </div>

            @if ($direcaoGeral)
                <div class="flex justify-center mb-16">
                    <div class="bg-etec-main p-8 rounded-2xl shadow-lg border border-etec-dark/30 dark:border-white/10 text-center max-w-sm w-full hover:-translate-y-1 transition duration-300">
                        <div class="relative hover:z-20 w-[129px] h-[129px] mx-auto bg-etec-dark text-white rounded-full flex items-center justify-center text-5xl mb-5 shadow-md">
                            @if ($direcaoGeral->photo)
                                <img src="{{ photo_url($direcaoGeral->photo) }}"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                                     class="w-full h-full object-cover rounded-full scale-[1.15] hover:scale-[1.4375] transition duration-700 ease-in-out">
                                <div style="display:none" class="w-full h-full flex items-center justify-center bg-etec-dark text-white font-bold text-2xl">{{ substr($direcaoGeral->name,0,1) }}</div>
                            @else
                                <svg class="w-14 h-14 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            @endif
                        </div>
                        <div class="inline-block bg-etec-accent/20 text-etec-accent text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide mb-3">
                            Direção Geral
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ $direcaoGeral->name }}</h3>
                        <span class="text-sm text-etec-light font-medium block mt-1 mb-3">{{ $direcaoGeral->role }}</span>
                        @if($direcaoGeral->bio)
                            <p class="text-blue-100 text-sm italic leading-relaxed">"{{ $direcaoGeral->bio }}"</p>
                        @endif
                        <div class="mt-5 pt-5 border-t border-white/10">
                            <a href="mailto:{{ $direcaoGeral->email }}"
                               class="inline-flex items-center gap-2 text-sm font-bold text-white hover:text-etec-accent transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Fale com a Direção
                            </a>
                        </div>
                    </div>
                </div>

                <div class="hidden md:flex items-center justify-center mb-12 gap-0">
                    <div class="w-px h-10 bg-gray-300 dark:bg-white/10"></div>
                </div>
                <div class="hidden md:block w-3/4 h-px bg-gray-200 dark:bg-white/10 mx-auto mb-12"></div>
            @endif

            @if($departamentos->count() > 0)
            @php
                $categorias = $departamentos
                    ->map(fn($d) => Str::contains(strtolower($d->role), 'professor') ? 'Professor' : 'Administrativo')
                    ->unique()->values()->sort()->prepend('Todos');
                $deptJson = $departamentos->map(fn($d) => [
                    'name'  => $d->name,
                    'role'  => $d->role,
                    'email' => $d->email ?? '',
                    'bio'   => $d->bio ?? '',
                    'cat'   => Str::contains(strtolower($d->role), 'professor') ? 'Professor' : 'Administrativo',
                ]);
            @endphp
            <div x-data="{
                    busca: '',
                    filtro: 'Todos',
                    equipe: {{ \Illuminate\Support\Js::from($deptJson) }},
                    get lista() {
                        return this.equipe.filter(p => {
                            const ok_cat = this.filtro === 'Todos' || p.cat === this.filtro;
                            const ok_busca = this.busca === '' ||
                                p.name.toLowerCase().includes(this.busca.toLowerCase()) ||
                                p.role.toLowerCase().includes(this.busca.toLowerCase());
                            return ok_cat && ok_busca;
                        });
                    }
                }">

                {{-- Barra de filtros --}}
                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-8 max-w-5xl mx-auto">
                    <div class="flex gap-2 flex-wrap">
                        @foreach($categorias as $cat)
                        <button @click="filtro = '{{ $cat }}'"
                                :class="filtro === '{{ $cat }}' ? 'bg-etec-accent text-etec-dark' : 'bg-white/10 text-white hover:bg-white/20'"
                                class="px-4 py-1.5 rounded-full text-xs font-bold transition">
                            {{ $cat }}
                        </button>
                        @endforeach
                    </div>
                    <div class="relative w-full sm:w-64">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input x-model="busca" type="search" placeholder="Buscar por nome ou cargo…"
                               class="w-full bg-white/10 border border-white/20 text-white placeholder-gray-400 text-sm rounded-lg pl-9 pr-4 py-2 focus:outline-none focus:ring-2 focus:ring-etec-accent">
                    </div>
                </div>

                {{-- Grid de cards --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <template x-for="dept in lista" :key="dept.name + dept.role">
                        <div class="bg-etec-main p-6 rounded-xl shadow-sm hover:shadow-md hover:shadow-etec-dark/30 transition border border-etec-dark/30 dark:border-white/10 group">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center group-hover:bg-etec-accent/30 transition flex-shrink-0">
                                    <template x-if="dept.cat === 'Professor'">
                                        <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </template>
                                    <template x-if="dept.cat !== 'Professor'">
                                        <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-white leading-tight truncate" x-text="dept.role"></h4>
                                    <span class="text-sm text-etec-light font-medium truncate block" x-text="dept.name"></span>
                                </div>
                            </div>
                            <template x-if="dept.bio">
                                <p class="text-sm text-blue-100 mb-4 line-clamp-2 leading-relaxed" x-text="dept.bio"></p>
                            </template>
                            <template x-if="dept.email">
                                <a :href="'mailto:' + dept.email"
                                   class="inline-flex items-center gap-1.5 text-xs text-blue-200/70 hover:text-etec-accent transition font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <span x-text="dept.email"></span>
                                </a>
                            </template>
                        </div>
                    </template>
                </div>

                <p x-show="lista.length === 0" class="text-center text-gray-400 py-12 text-sm">
                    Nenhum resultado para "<span x-text="busca"></span>".
                </p>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection
