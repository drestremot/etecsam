@extends('layouts.app')

@section('content')

{{-- Hero Section --}}
@php
    $defaultSlidesJs = [
        [
            'image'       => asset('imagens/cursos/agropecuaria.jpg'),
            'title'       => 'Etec Sebastiana Augusta de Moraes',
            'badge'       => 'Ensino Técnico de Excelência',
            'description' => 'Formando profissionais de destaque para o agronegócio, tecnologia e gestão com ensino 100% gratuito e de excelência.',
        ],
        [
            'image'       => asset('imagens/cursos/desenvolvimento_sistemas.jpg'),
            'title'       => 'Escola Fazenda — Aprender Fazendo',
            'badge'       => 'Prática Profissional Real',
            'description' => 'Nossos alunos vivenciam a agropecuária e a tecnologia na prática: da lavoura à pecuária, dos laboratórios aos sistemas modernos.',
        ],
        [
            'image'       => asset('imagens/cursos/administracao.jpg'),
            'title'       => 'Educação Pública e Gratuita de Qualidade',
            'badge'       => 'Centro Paula Souza',
            'description' => 'A melhor estrutura de ensino técnico do Estado de São Paulo, conectando nossos alunos às melhores oportunidades.',
        ],
    ];

    if ($slides->isEmpty()) {
        $heroSlidesJs = collect($defaultSlidesJs);
    } else {
        $adminSlidesJs = $slides->map(fn($s) => [
            'image'       => $s->image ? photo_url($s->image) : $defaultSlidesJs[0]['image'],
            'title'       => $s->title,
            'badge'       => 'Destaque Etec SAM',
            'description' => $s->description,
        ]);
        $heroSlidesJs = collect([$defaultSlidesJs[0]])->merge($adminSlidesJs);
    }
@endphp

<section class="relative bg-[#0c1b33] min-h-[560px] lg:min-h-[620px] flex items-center overflow-hidden text-white"
         style="background-color: #0c1b33; color: #ffffff;"
          x-data="{
              current: 0,
              slides: {{ \Illuminate\Support\Js::from($heroSlidesJs) }},
              init() {
                  if (this.slides.length > 1) {
                      setInterval(() => { this.current = (this.current + 1) % this.slides.length }, 6500);
                  }
              }
          }">

    {{-- Background Slides com Overlay de Alto Contraste e Maior Transparência --}}
    <template x-for="(slide, i) in slides" :key="i">
        <div class="absolute inset-0 z-0 transition-opacity duration-1000" x-show="current === i" x-transition.opacity.duration.1000ms>
            <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover opacity-65 scale-105 transition-transform duration-10000 pointer-events-none" onerror="this.style.display='none'">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0c1b33]/95 via-[#0c1b33]/70 to-[#0c1b33]/30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[#0c1b33]/80 via-transparent to-black/20"></div>
        </div>
    </template>

    <div class="container mx-auto px-4 relative z-10 py-16 lg:py-24">
        <div class="max-w-3xl text-white space-y-6">

            {{-- Floating Badge --}}
            <div class="inline-flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-1.5 rounded-full border border-white/20 shadow-sm">
                <span class="w-2.5 h-2.5 bg-amber-400 rounded-full animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest uppercase text-amber-300" x-text="slides[current].badge || 'Etec SAM &bull; Andradina'">Centro Paula Souza</span>
            </div>

            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.15] drop-shadow-md text-white" x-text="slides[current].title">
                {{ $defaultSlidesJs[0]['title'] }}
            </h1>

            <p class="text-sm sm:text-lg text-slate-200 leading-relaxed border-l-4 border-amber-400 pl-4 max-w-2xl font-normal drop-shadow-xs"
               x-show="slides[current].description" x-text="slides[current].description">
                {{ $defaultSlidesJs[0]['description'] }}
            </p>

            <div class="flex flex-wrap items-center gap-4 pt-3">
                <a href="#unidades"
                   style="background-color: #f59e0b; color: #020617;"
                   class="inline-flex items-center gap-2.5 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-extrabold px-7 py-3.5 rounded-2xl shadow-lg hover:from-amber-300 hover:to-amber-400 hover:shadow-xl hover:-translate-y-0.5 transition duration-200 text-sm sm:text-base">
                    <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>Conheça os Cursos</span>
                </a>
                <a href="{{ route('institutional') }}"
                   style="background-color: rgba(255, 255, 255, 0.12); color: #ffffff; border: 1px solid rgba(255, 255, 255, 0.25);"
                   class="inline-flex items-center gap-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold px-6 py-3.5 rounded-2xl transition duration-200 text-sm sm:text-base">
                    <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>Nossa História</span>
                </a>
            </div>

            {{-- Atalhos por Perfil do Usuário com Ícones SVG --}}
            <div class="flex flex-wrap items-center gap-x-6 gap-y-3 pt-6 border-t border-white/15 text-xs sm:text-sm">
                <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-amber-300 hover:text-white font-semibold transition">
                    <div class="w-6 h-6 rounded-full bg-amber-400/20 flex items-center justify-center text-amber-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    </div>
                    <span>Quero Estudar Aqui</span>
                </a>
                <a href="https://nsaetec.com.br/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-slate-200 hover:text-amber-300 font-semibold transition">
                    <div class="w-6 h-6 rounded-full bg-blue-400/20 flex items-center justify-center text-blue-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span>Portal do Aluno (NSA)</span>
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 text-slate-200 hover:text-amber-300 font-semibold transition">
                    <div class="w-6 h-6 rounded-full bg-emerald-400/20 flex items-center justify-center text-emerald-300">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span>Empresa Parceira</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Indicadores de Slides --}}
    <div class="absolute bottom-6 left-0 right-0 flex justify-center gap-2 z-10" x-show="slides.length > 1">
        <template x-for="(slide, i) in slides" :key="i">
            <button @click="current = i" :class="current === i ? 'bg-amber-400 w-9' : 'bg-white/40 w-2.5'"
                    class="h-2.5 rounded-full transition-all duration-300 focus:outline-none" :aria-label="'Slide ' + (i + 1)"></button>
        </template>
    </div>
</section>

{{-- Faixa de Indicadores & Estatísticas da Escola --}}
<div class="bg-[#0f223f] border-b border-white/10 shadow-md text-white" style="background-color: #0f223f; color: #ffffff;">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-white/10">

            <div class="py-6 px-4 text-center group hover:bg-white/5 transition rounded-2xl">
                <div class="w-11 h-11 rounded-2xl bg-amber-400/15 text-amber-300 flex items-center justify-center mx-auto mb-2.5 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">+{{ date('Y') - 1991 }} Anos</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Tradição e excelência no ensino</div>
            </div>

            <div class="py-6 px-4 text-center group hover:bg-white/5 transition rounded-2xl">
                <div class="w-11 h-11 rounded-2xl bg-blue-400/15 text-blue-300 flex items-center justify-center mx-auto mb-2.5 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ $units->count() }} Polos</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Unidades em toda a região</div>
            </div>

            <div class="py-6 px-4 text-center group hover:bg-white/5 transition rounded-2xl">
                <div class="w-11 h-11 rounded-2xl bg-emerald-400/15 text-emerald-300 flex items-center justify-center mx-auto mb-2.5 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">{{ ($sectors ?? collect())->count() }}+ Setores</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Laboratórios e Escola Fazenda</div>
            </div>

            <div class="py-6 px-4 text-center group hover:bg-white/5 transition rounded-2xl">
                <div class="w-11 h-11 rounded-2xl bg-purple-400/15 text-purple-300 flex items-center justify-center mx-auto mb-2.5 shadow-2xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">100% Gratuito</div>
                <div class="text-xs text-slate-300 font-medium mt-1">Governo do Estado de São Paulo</div>
            </div>

        </div>
    </div>
</div>

{{-- Nossas Unidades e Polos de Ensino --}}
<section id="unidades" class="py-16 sm:py-20 bg-[#0b172a] text-white border-b border-white/10" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">

        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3.5 py-1 rounded-full border border-amber-400/25 inline-block">
                Polos de Ensino
            </span>
            <h2 class="text-2xl sm:text-4xl font-extrabold text-white mt-3 tracking-tight">
                Nossas Unidades Escolares
            </h2>
            <p class="text-sm sm:text-base text-slate-300 mt-2">
                Conheça a Sede em Andradina e as extensões que levam a formação técnica às cidades da nossa região.
            </p>
            <div class="w-16 h-1 bg-amber-400 mx-auto mt-4 rounded-full"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($units))
                @foreach($units as $unit)
                    <a href="{{ route('units.show', $unit->id) }}"
                       class="group bg-[#14284b] hover:bg-[#1a335f] rounded-3xl shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden border border-white/10 hover:border-amber-400/50 flex flex-col justify-between">

                        {{-- Capa com Foto e Gradiente --}}
                        <div class="h-48 bg-[#0c1b33] relative overflow-hidden flex items-center justify-center">
                            @if($unit->image)
                                <img src="{{ photo_url($unit->image) }}" alt="{{ $unit->name }}"
                                     class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                     onerror="this.onerror=null;this.src='{{ avatar_url($unit->name, '0f223f', 'fff', ['bold' => 'true', 'size' => 512]) }}'">
                                <div class="absolute inset-0 bg-gradient-to-t from-[#14284b] via-[#14284b]/30 to-transparent"></div>
                            @else
                                <div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-[#0f223f] to-[#16325c] p-6 text-center">
                                    <svg class="w-10 h-10 text-white/50 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    <span class="text-white/80 text-xs font-bold uppercase tracking-widest">{{ $unit->city }}</span>
                                </div>
                            @endif

                            <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between z-10">
                                <span class="inline-flex items-center gap-1.5 text-white text-xs font-bold bg-black/60 backdrop-blur-md px-3 py-1 rounded-full border border-white/20">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $unit->city }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-300 bg-amber-400/20 backdrop-blur-md px-2.5 py-1 rounded-full border border-amber-400/30">
                                    {{ $unit->courses_count }} {{ $unit->courses_count == 1 ? 'curso' : 'cursos' }}
                                </span>
                            </div>
                        </div>

                        {{-- Conteúdo do Cartão --}}
                        <div class="p-6 flex flex-col flex-grow justify-between space-y-4">
                            <div>
                                <h3 class="text-lg font-bold text-white group-hover:text-amber-300 transition leading-snug">
                                    {{ $unit->name }}
                                </h3>
                                <p class="text-xs text-slate-300 mt-1 line-clamp-2 leading-relaxed">
                                    {{ $unit->address ?? 'Unidade oficial do Centro Paula Souza na região de Andradina.' }}
                                </p>
                            </div>

                            <div class="pt-4 border-t border-white/10 flex items-center justify-between">
                                <span class="text-xs font-semibold text-slate-400">Ver cursos disponíveis</span>
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-400 group-hover:translate-x-1 transition duration-200">
                                    Acessar polo &rarr;
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- Escola Fazenda & Laboratórios Didáticos --}}
<section id="fazenda" class="py-16 sm:py-20 bg-[#0f223f] text-white relative overflow-hidden" style="background-color: #0f223f; color: #ffffff;">
    <div class="container mx-auto px-4 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 border-b border-white/10 pb-6 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3.5 py-1 rounded-full border border-amber-400/25 inline-block">
                    Infraestrutura Prática
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold mt-3 tracking-tight text-white">Escola Fazenda & Laboratórios</h2>
                <p class="text-slate-200 mt-2 text-sm max-w-xl leading-relaxed">
                    Ambientes reais de produção agropecuária, tecnologia e pesquisa integrados ao currículo dos nossos cursos técnicos.
                </p>
            </div>
            <a href="{{ route('institutional') }}" class="inline-flex items-center gap-2 border border-white/30 text-white text-xs font-bold px-5 py-2.5 rounded-xl hover:bg-white/10 hover:border-amber-400 transition">
                <span>Conheça Toda a Estrutura</span>
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @if(isset($sectors))
                @foreach($sectors as $sector)
                <a href="{{ route('sectors.show', $sector->slug) }}"
                   class="block group bg-[#14284b] hover:bg-[#1a335f] p-5 sm:p-6 rounded-2xl transition duration-300 border border-white/10 hover:border-amber-400/50 hover:-translate-y-1 shadow-sm">
                    <div class="w-12 h-12 bg-amber-400/15 text-amber-300 rounded-2xl flex items-center justify-center mb-4 group-hover:bg-amber-400/25 transition">
                        @switch($sector->icon)
                            @case('cow')
                                {{-- Ícone Pecuária / Bovinos --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                @break
                            @case('factory')
                                {{-- Ícone Agroindústria --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                @break
                            @case('computer')
                                {{-- Ícone Informática / Tecnologia --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                @break
                            @case('flask')
                                {{-- Ícone Laboratório Químico / Científico --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                                @break
                            @case('leaf')
                            @case('tree')
                                {{-- Ícone Agronomia / Lavouras --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                @break
                            @default
                                {{-- Ícone Agrícola Padrão --}}
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                        @endswitch
                    </div>
                    <h3 class="font-bold text-base mb-1 leading-snug text-white group-hover:text-amber-300 transition">{{ $sector->name }}</h3>
                    <p class="text-xs text-slate-300 leading-relaxed line-clamp-2">{{ $sector->summary }}</p>
                    <div class="mt-4 flex items-center gap-1 text-xs text-amber-400 font-semibold opacity-0 group-hover:opacity-100 transition">
                        <span>Ver detalhes</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- Próximos Eventos & Calendário --}}
@if($nextEvents->count() > 0)
<section class="py-16 sm:py-20 bg-[#0b172a] text-white border-b border-white/10" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-amber-400/15 px-3.5 py-1 rounded-full border border-amber-400/25 inline-block">
                    Calendário & Atividades
                </span>
                <h2 class="text-2xl sm:text-4xl font-extrabold text-white mt-3 tracking-tight">Próximos Eventos</h2>
            </div>
            <a href="{{ route('agenda') }}" class="inline-flex items-center gap-2 text-amber-400 text-xs font-bold hover:underline transition">
                <span>Ver Agenda Completa</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-6">
            @foreach($nextEvents as $evento)
            @php
                $data = \Carbon\Carbon::parse($evento->start_date);
            @endphp
            <a href="{{ route('agenda') }}"
               class="group flex gap-5 bg-[#14284b] hover:bg-[#1a335f] rounded-3xl p-6 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 border border-white/10 hover:border-amber-400/50">
                <div class="flex-shrink-0 w-16 text-center">
                    <div class="bg-gradient-to-b from-[#0f223f] to-[#0c1b33] text-white rounded-2xl py-3 px-1 border border-white/10 shadow-xs">
                        <span class="block text-2xl font-black leading-none text-amber-300">{{ $data->format('d') }}</span>
                        <span class="block text-[10px] font-bold uppercase tracking-wider mt-1 text-slate-300">{{ $data->translatedFormat('M') }}</span>
                    </div>
                </div>
                <div class="min-w-0 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-white text-sm leading-snug group-hover:text-amber-300 transition line-clamp-2">
                            {{ $evento->title }}
                        </h3>
                        @if($evento->location ?? null)
                        <p class="text-xs text-slate-300 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="truncate">{{ $evento->location }}</span>
                        </p>
                        @endif
                    </div>
                    @if($evento->category ?? null)
                    <div class="mt-3">
                        <span class="inline-block text-[11px] bg-amber-400/15 text-amber-300 px-2.5 py-0.5 rounded-full font-semibold border border-amber-400/25">
                            {{ $evento->category }}
                        </span>
                    </div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Banner CTA Vestibulinho --}}
<section class="bg-gradient-to-r from-[#0c1b33] via-[#16325c] to-[#0c1b33] py-16 sm:py-20 text-white relative overflow-hidden border-t border-white/10">
    <div class="container mx-auto px-4 text-center relative z-10 max-w-3xl">
        <span class="text-xs font-extrabold uppercase tracking-widest text-amber-300 bg-white/10 px-4 py-1.5 rounded-full border border-white/20 inline-block">
            Processo Seletivo 2026
        </span>
        <h2 class="text-3xl sm:text-5xl font-extrabold mt-4 mb-4 tracking-tight leading-tight text-white">
            Venha fazer parte da Etec SAM
        </h2>
        <p class="text-slate-200 text-sm sm:text-base mb-8 max-w-xl mx-auto leading-relaxed">
            Inscrições abertas para o Vestibulinho do Centro Paula Souza. Ensino técnico de excelência, 100% público e gratuito.
        </p>
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-950 font-bold px-8 py-4 rounded-2xl shadow-xl hover:from-amber-300 hover:to-amber-400 hover:scale-105 transition duration-200 text-sm sm:text-base">
                <svg class="w-5 h-5 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <span>Inscreva-se no Vestibulinho</span>
            </a>
            <a href="{{ route('contact') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/30 text-white font-semibold px-8 py-4 rounded-2xl transition duration-200 text-sm sm:text-base">
                <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>Fale com Nossa Secretaria</span>
            </a>
        </div>
    </div>
</section>

@endsection
