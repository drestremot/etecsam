@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center flex-shrink-0 border border-white/20">
            @switch($sector->icon)
                @case('cow')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6c-1 0-2 .5-2 1.5S11 9 11 10c0 2-2 3-2 5s1.5 3 3 3 3-1 3-3-2-3-2-5c0-1 1-1.5 1-2.5S13 6 12 6zM9.5 6C8 4.5 6 5 6 5M14.5 6C16 4.5 18 5 18 5"/></svg>
                    @break
                @case('pig')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="7" ry="5" stroke-width="1.5"/><circle cx="10" cy="11" r="1" fill="currentColor"/><circle cx="14" cy="11" r="1" fill="currentColor"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 10c-1-1-2.5-.5-3 1M18 10c1-1 2.5-.5 3 1"/></svg>
                    @break
                @case('chicken')
                @case('bee')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4a3 3 0 100 6 3 3 0 000-6zM6.343 7.657a8 8 0 1011.314 0M12 10v10M9 16l3 3 3-3"/></svg>
                    @break
                @case('fish')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8c0 0-3-4-8-4S3 8 3 12s3 8 7 8c5 0 8-4 8-4l3-4-3-4z"/><circle cx="8" cy="11" r="1" fill="currentColor"/></svg>
                    @break
                @case('leaf')
                @case('tree')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22V12m0 0c0 0-6-3-6-8a6 6 0 0112 0c0 5-6 8-6 8z"/></svg>
                    @break
                @case('factory')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21V9l5-3v3l5-3v3l5-3v12H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21v-4h3v4M14 21v-4h3v4"/></svg>
                    @break
                @case('computer')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke-width="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 21h8M12 17v4"/></svg>
                    @break
                @case('flask')
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6m-5 0v6L5 19a2 2 0 002 2h10a2 2 0 002-2L14 9V3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 15h14"/></svg>
                    @break
                @default
                    <svg class="w-10 h-10 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            @endswitch
        </div>
        <div>
            <span class="text-etec-accent font-bold tracking-widest uppercase text-xs mb-2 block">
                Unidade Didática &amp; Laboratório
            </span>
            <h1 class="text-3xl md:text-5xl font-bold leading-tight">
                {{ $sector->name }}
            </h1>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <div class="grid lg:grid-cols-3 gap-12">

        <div class="lg:col-span-2 space-y-8">

            {{-- Carrossel --}}
            @if(!empty($sector->images))
            <div class="relative rounded-2xl overflow-hidden shadow-2xl bg-gray-900 aspect-video group">
                <div class="flex overflow-x-auto snap-x snap-mandatory h-full w-full" style="scroll-behavior: smooth;">
                    @foreach($sector->images as $image)
                    <div class="snap-center flex-shrink-0 w-full h-full relative">
                        <img src="{{ $image }}" class="w-full h-full object-cover scale-[1.15] group-hover:scale-[1.4375] transition duration-700 ease-in-out">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    @endforeach
                </div>
                <div class="absolute bottom-4 left-0 right-0 flex justify-center">
                    <span class="inline-flex items-center gap-2 bg-black/40 backdrop-blur-sm text-white/70 text-xs font-medium px-3 py-1.5 rounded-full">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Deslize para ver mais fotos
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </span>
                </div>
            </div>
            @endif

            {{-- Descrição --}}
            <div>
                <h3 class="text-2xl font-bold text-etec-dark dark:text-white mb-4 border-l-4 border-etec-accent pl-4"
                   >
                    Finalidade Pedagógica
                </h3>
                <p class="text-gray-700 dark:text-gray-300 text-lg leading-relaxed">
                    {{ $sector->description ?? $sector->summary }}
                </p>

                <div class="bg-etec-main border border-etec-dark/30 dark:border-white/10 rounded-xl p-6 mt-6">
                    <h4 class="font-bold text-white mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        Cursos Atendidos
                    </h4>
                    <ul class="space-y-2 text-sm text-green-100">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            MTEC-PI em Agropecuária
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            MTEC-PI em Agronegócio
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            Técnico em Florestas
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            Técnico em Zootecnia
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-etec-main p-6 rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 sticky top-6">
                <h4 class="font-bold text-white text-lg mb-5">Destaques</h4>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22V12m0 0c0 0-6-3-6-8a6 6 0 0112 0c0 5-6 8-6 8z"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white text-sm">Sustentabilidade</strong>
                            <span class="text-xs text-green-200/70">Práticas alinhadas com o meio ambiente.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 text-green-700 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white text-sm">Tecnologia</strong>
                            <span class="text-xs text-green-200/70">Equipamentos modernos e digitais.</span>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-yellow-100 text-yellow-700 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div>
                            <strong class="block text-white text-sm">Segurança</strong>
                            <span class="text-xs text-green-200/70">Uso obrigatório de EPIs.</span>
                        </div>
                    </li>
                </ul>

                <div class="mt-8 pt-6 border-t border-white/10">
                    <a href="{{ route('home') }}#fazenda"
                       class="flex items-center justify-center gap-2 w-full py-3 border-2 border-white/30 text-white font-bold rounded-xl hover:bg-etec-accent hover:text-etec-dark hover:border-etec-accent transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Voltar para Unidades
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
