@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-r from-[#0c1b33] via-[#14284b] to-[#0c1b33] text-white py-14 border-b border-white/10 shadow-md" style="background-color: #0c1b33; color: #ffffff;">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-18 h-18 bg-amber-400/20 rounded-3xl flex items-center justify-center flex-shrink-0 border border-amber-400/30 text-amber-300 shadow-md">
            @switch($sector->icon)
                @case('cow')
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    @break
                @case('factory')
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    @break
                @case('computer')
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    @break
                @case('flask')
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    @break
                @case('leaf')
                @case('tree')
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @break
                @default
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
            @endswitch
        </div>
        <div>
            <span class="text-amber-300 font-extrabold tracking-widest uppercase text-xs mb-1.5 block">
                Unidade Didática &amp; Laboratório
            </span>
            <h1 class="text-3xl sm:text-5xl font-extrabold leading-tight text-white tracking-tight">
                {{ $sector->name }}
            </h1>
        </div>
    </div>
</div>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2 space-y-8">

                {{-- Carrossel de Fotos --}}
                @if(!empty($sector->images))
                <div class="relative rounded-3xl overflow-hidden shadow-xl bg-slate-900 aspect-video group border border-white/10">
                    <div class="flex overflow-x-auto snap-x snap-mandatory h-full w-full" style="scroll-behavior: smooth;">
                        @foreach($sector->images as $image)
                        <div class="snap-center flex-shrink-0 w-full h-full relative">
                            <img src="{{ $image }}" class="w-full h-full object-cover transition duration-700 ease-in-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                        </div>
                        @endforeach
                    </div>
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                        <span class="inline-flex items-center gap-2 bg-black/50 backdrop-blur-md text-white/90 text-xs font-medium px-3.5 py-1.5 rounded-full border border-white/20">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            Deslize para ver mais fotos
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
                @endif

                {{-- Descrição & Finalidade --}}
                <div class="bg-[#14284b] p-8 rounded-3xl border border-white/10 shadow-sm space-y-6">
                    <div>
                        <h3 class="text-2xl font-extrabold text-white mb-3 flex items-center gap-2.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span>Finalidade Pedagógica</span>
                        </h3>
                        <p class="text-slate-200 text-base sm:text-lg leading-relaxed font-normal">
                            {{ $sector->description ?? $sector->summary }}
                        </p>
                    </div>

                    <div class="bg-[#0f223f] border border-white/10 rounded-2xl p-6">
                        <h4 class="font-extrabold text-white mb-3 flex items-center gap-2 text-sm uppercase tracking-wide">
                            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                            <span>Cursos Atendidos Diretamente</span>
                        </h4>
                        <ul class="space-y-2.5 text-sm text-slate-200">
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span>M-TEC Integrado em Agropecuária</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span>M-TEC Integrado em Agronegócio</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span>Técnico em Agroindústria & Alimentos</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                <span>Técnico em Zootecnia & Manejo</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-[#14284b] p-6 rounded-3xl shadow-sm border border-white/10 sticky top-24">
                    <h4 class="font-extrabold text-white text-base mb-5 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        <span>Destaques da Instalação</span>
                    </h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3.5">
                            <div class="w-10 h-10 bg-emerald-400/15 text-emerald-300 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22V12m0 0c0 0-6-3-6-8a6 6 0 0112 0c0 5-6 8-6 8z"/></svg>
                            </div>
                            <div>
                                <strong class="block text-white text-sm">Sustentabilidade</strong>
                                <span class="text-xs text-slate-300">Práticas de manejo ecológico.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3.5">
                            <div class="w-10 h-10 bg-blue-400/15 text-blue-300 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <strong class="block text-white text-sm">Tecnologia</strong>
                                <span class="text-xs text-slate-300">Equipamentos e instrumentação moderna.</span>
                            </div>
                        </li>
                        <li class="flex items-start gap-3.5">
                            <div class="w-10 h-10 bg-amber-400/15 text-amber-300 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <div>
                                <strong class="block text-white text-sm">Normas & Segurança</strong>
                                <span class="text-xs text-slate-300">Ambiente seguro com uso de EPIs.</span>
                            </div>
                        </li>
                    </ul>

                    <div class="mt-8 pt-6 border-t border-white/10">
                        <a href="{{ route('home') }}#fazenda"
                           class="flex items-center justify-center gap-2 w-full py-3 bg-[#0f223f] hover:bg-amber-400 text-white hover:text-slate-950 font-bold rounded-2xl transition text-xs border border-white/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                            <span>Voltar para Todos os Setores</span>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
