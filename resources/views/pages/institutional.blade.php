@extends('layouts.app')

@section('content')

{{-- Page header --}}
<x-page-header variant="plain" label="Institucional" title="Nossa História & Tradição"
    subtitle="Conheça a trajetória da Etec Sebastiana Augusta de Moraes e nosso compromisso com a excelência no ensino técnico em Andradina e região." />

<div class="py-14 bg-[#0b172a] text-white" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">

        {{-- História & Destaques --}}
        <div class="grid md:grid-cols-2 gap-12 items-center mb-20">
            <div class="relative group">
                <img src="https://images.unsplash.com/photo-1560493676-04071c5f467b?q=80&w=1000&auto=format&fit=crop"
                     alt="Fachada da Escola" class="rounded-3xl shadow-xl w-full object-cover group-hover:scale-[1.02] transition duration-500 border border-white/10">
                <div class="absolute -bottom-4 -right-4 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-black text-sm px-6 py-3.5 rounded-2xl shadow-xl">
                    Desde 1994
                </div>
            </div>

            <div class="space-y-6 text-slate-200 leading-relaxed">
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight mb-3">
                        Tradição e Inovação no Campo
                    </h2>
                    <p class="text-sm sm:text-base text-slate-300 leading-relaxed font-normal">
                        Desde sua fundação, a <strong class="text-white font-bold">Etec Sebastiana Augusta de Moraes</strong> tem sido um pilar fundamental no desenvolvimento de Andradina e do Oeste Paulista. Somos o ambiente onde germinam a pesquisa, a prática e o futuro do agronegócio e da tecnologia.
                    </p>
                </div>

                <div class="bg-[#14284b] border border-white/10 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-extrabold text-base text-white mb-1.5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                        <span>Raízes Fortes — Integração ao Centro Paula Souza</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Em 1994, a escola foi oficialmente integrada ao Centro Paula Souza (CPS), unindo a vocação regional à metodologia e infraestrutura da principal rede de ensino técnico da América Latina.
                    </p>
                </div>

                <div class="bg-[#14284b] border border-white/10 rounded-3xl p-6 shadow-sm">
                    <h3 class="font-extrabold text-base text-white mb-1.5 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                        <span>O Diferencial "Escola-Fazenda"</span>
                    </h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        Nossos alunos vivenciam a rotina produtiva real: do manejo do solo à colheita, da zootecnia aos laboratórios de tecnologia e informática, aliando teoria e prática diariamente.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-[#14284b] border border-white/10">
                        <div class="w-9 h-9 bg-amber-400/15 rounded-xl flex items-center justify-center flex-shrink-0 text-amber-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-white block">Inovação</strong>
                            <span class="text-[11px] text-slate-400">Tecnologia no campo</span>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-[#14284b] border border-white/10">
                        <div class="w-9 h-9 bg-emerald-400/15 rounded-xl flex items-center justify-center flex-shrink-0 text-emerald-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        <div>
                            <strong class="text-xs font-bold text-white block">Sustentabilidade</strong>
                            <span class="text-[11px] text-slate-400">Agroecologia e manejo</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Estrutura Administrativa & Equipe --}}
        <div class="bg-[#0f223f] rounded-3xl py-16 px-6 sm:px-10 border border-white/10 shadow-md">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3.5 py-1 rounded-full border border-amber-400/25 inline-block">
                    Governança
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white mt-3 tracking-tight">
                    Estrutura Administrativa & Equipe
                </h2>
                <div class="w-16 h-1 bg-amber-400 mx-auto mt-4 rounded-full"></div>
            </div>

            @if ($direcaoGeral)
                <div class="flex justify-center mb-16">
                    <div class="bg-[#14284b] p-8 rounded-3xl shadow-xl border border-white/10 text-center max-w-sm w-full hover:-translate-y-1 transition duration-300">
                        <x-avatar :name="$direcaoGeral->name" :photo="$direcaoGeral->photo" :size="120" text-size="text-4xl" class="mx-auto mb-5 shadow-md border-4 border-amber-400/30" />
                        <div class="inline-block bg-amber-400/15 text-amber-300 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-2 border border-amber-400/25">
                            Direção da Unidade
                        </div>
                        <h3 class="text-xl font-bold text-white">{{ $direcaoGeral->name }}</h3>
                        <span class="text-xs font-semibold text-slate-300 block mt-1 mb-3">{{ $direcaoGeral->role }}</span>
                        @if($direcaoGeral->bio)
                            <p class="text-xs text-slate-300 italic leading-relaxed">"{{ $direcaoGeral->bio }}"</p>
                        @endif
                        <div class="mt-5 pt-5 border-t border-white/10">
                            <a href="mailto:{{ $direcaoGeral->email }}"
                               class="inline-flex items-center gap-2 text-xs font-bold text-amber-400 hover:underline transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span>Fale com a Direção</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if($departamentos->count() > 0)
            @php
                $isProf = fn($d) => str_contains(strtolower($d->role ?? ''), 'professor');
                $categorias = $departamentos
                    ->map(fn($d) => $isProf($d) ? 'Docente' : 'Administrativo')
                    ->unique()->values()->sort()->prepend('Todos');
                $deptJson = $departamentos->map(fn($d) => [
                    'name'  => $d->name  ?? '',
                    'role'  => $d->role  ?? '',
                    'email' => $d->email ?? '',
                    'bio'   => $d->bio   ?? '',
                    'cat'   => $isProf($d) ? 'Docente' : 'Administrativo',
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
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between mb-8 max-w-5xl mx-auto">
                    <div class="flex gap-2 flex-wrap">
                        @foreach($categorias as $cat)
                        <button @click="filtro = '{{ $cat }}'"
                                :class="filtro === '{{ $cat }}' ? 'bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-extrabold shadow-sm' : 'bg-[#14284b] hover:bg-[#1a335f] text-slate-200 border border-white/10'"
                                class="px-4 py-2 rounded-xl text-xs font-semibold transition duration-200">
                            {{ $cat }}
                        </button>
                        @endforeach
                    </div>
                    <div class="relative w-full sm:w-72">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input x-model="busca" type="search" placeholder="Buscar por nome ou cargo…"
                               class="w-full bg-[#0b172a] border border-white/15 text-white placeholder-slate-400 text-xs rounded-xl pl-10 pr-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-amber-400">
                    </div>
                </div>

                {{-- Grid de cards --}}
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
                    <template x-for="dept in lista" :key="dept.name + dept.role">
                        <div class="bg-[#14284b] hover:bg-[#1a335f] p-6 rounded-3xl shadow-sm hover:shadow-lg transition-all duration-300 border border-white/10 hover:border-amber-400/50 group flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-3.5 mb-4">
                                    <div class="w-10 h-10 rounded-2xl bg-amber-400/15 text-amber-300 flex items-center justify-center flex-shrink-0">
                                        <template x-if="dept.cat === 'Docente'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                        </template>
                                        <template x-if="dept.cat !== 'Docente'">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </template>
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-bold text-white text-sm leading-tight truncate group-hover:text-amber-300 transition" x-text="dept.role"></h4>
                                        <span class="text-xs text-slate-300 font-medium truncate block mt-0.5" x-text="dept.name"></span>
                                    </div>
                                </div>
                                <template x-if="dept.bio">
                                    <p class="text-xs text-slate-300 mb-4 line-clamp-2 leading-relaxed" x-text="dept.bio"></p>
                                </template>
                            </div>
                            <template x-if="dept.email">
                                <div class="pt-3 border-t border-white/10">
                                    <a :href="'mailto:' + dept.email"
                                       class="inline-flex items-center gap-1.5 text-xs text-amber-400 hover:text-amber-300 hover:underline font-semibold truncate max-w-full">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <span x-text="dept.email" class="truncate"></span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>

                <p x-show="lista.length === 0" class="text-center text-slate-400 py-12 text-sm">
                    Nenhum colaborador encontrado para "<span x-text="busca"></span>".
                </p>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection
