@extends('layouts.app')

@section('content')

{{-- Hero --}}
<section class="relative bg-etec-dark min-h-[520px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=80&w=1600&auto=format&fit=crop"
             alt="Paisagem agrícola" class="w-full h-full object-cover opacity-60">
        <div class="absolute inset-0 bg-gradient-to-r from-etec-dark/80 via-etec-dark/50 to-transparent"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10 py-20">
        <div class="max-w-2xl text-white space-y-6">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-1.5 rounded-full border border-white/20">
                <span class="w-2 h-2 bg-etec-accent rounded-full animate-pulse"></span>
                <span class="text-xs font-bold tracking-widest uppercase">Portal Institucional</span>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                Etec <span class="text-etec-accent">Sebastiana Augusta</span><br>de Moraes
            </h1>

            <p class="text-lg text-gray-200 leading-relaxed border-l-4 border-etec-accent pl-4">
                Ensino técnico de excelência, integrado à prática do campo.<br>
                Formando profissionais para o agronegócio e a tecnologia.
            </p>

            <div class="flex flex-wrap gap-4 pt-2">
                <a href="{{ route('home') }}#unidades"
                   class="inline-flex items-center gap-2 bg-etec-accent text-etec-dark font-bold px-6 py-3 rounded-lg hover:bg-yellow-400 transition shadow-lg">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    Conheça os Cursos
                </a>
                <a href="{{ route('institutional') }}"
                   class="inline-flex items-center gap-2 border border-white/40 text-white px-6 py-3 rounded-lg hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Nossa História
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
            <div class="py-5 px-6 text-center">
                <div class="text-2xl font-bold text-etec-dark">+30</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Anos de história</div>
            </div>
            <div class="py-5 px-6 text-center">
                <div class="text-2xl font-bold text-etec-dark">4</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Unidades de ensino</div>
            </div>
            <div class="py-5 px-6 text-center">
                <div class="text-2xl font-bold text-etec-dark">12+</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Laboratórios ativos</div>
            </div>
            <div class="py-5 px-6 text-center">
                <div class="text-2xl font-bold text-etec-dark">100%</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">Ensino público e gratuito</div>
            </div>
        </div>
    </div>
</div>

{{-- Unidades --}}
<section id="unidades" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            <span class="text-etec-medium font-bold uppercase tracking-widest text-xs">Locais de Ensino</span>
            <h2 class="text-3xl font-bold text-etec-dark mt-2">Nossas Unidades</h2>
            <div class="w-16 h-1 bg-etec-accent mx-auto mt-4"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @if(isset($units))
                @foreach($units as $unit)
                    <a href="{{ route('units.show', $unit->id) }}"
                       class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col">

                        <div class="h-44 bg-gradient-to-br from-etec-dark to-etec-main relative overflow-hidden flex items-center justify-center">
                            <div class="relative w-full h-full">
                                @if($unit->image)
                                    {{-- Foto de capa preenchendo todo o card --}}
                                    <img src="{{ photo_url($unit->image) }}" alt="{{ $unit->name }}"
                                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-in-out"
                                         onerror="this.onerror=null;this.src='{{ avatar_url($unit->name, '1a3a6e', 'fff', ['bold' => 'true', 'size' => 512]) }}'">
                                    {{-- Gradiente sutil para legibilidade do badge inferior --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/10 to-transparent"></div>
                                @endif

                                <div class="unit-card-fallback @if($unit->image) hidden @endif relative z-10 bg-white/10 backdrop-blur-sm p-4 rounded-xl border border-white/20 group-hover:bg-white/20 transition">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-3 left-4 z-10">
                                <span class="inline-flex items-center gap-1.5 text-white text-xs font-bold bg-black/50 backdrop-blur-sm px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $unit->city }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition leading-tight">
                                {{ $unit->name }}
                            </h3>
                            <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100">
                                <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-etec-dark bg-etec-light/40 px-3 py-1.5 rounded-full">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                                    {{ $unit->courses_count }} Cursos
                                </span>
                                <span class="flex items-center gap-1 text-xs font-bold text-etec-medium group-hover:text-etec-accent transition">
                                    Ver cursos
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- Setores / Escola Fazenda --}}
<section id="fazenda" class="py-20 bg-etec-dark text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid slice">
            <defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"/></pattern></defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-14 border-b border-white/10 pb-8 gap-4">
            <div>
                <span class="text-etec-accent font-bold uppercase tracking-widest text-xs">Escola Fazenda</span>
                <h2 class="text-3xl font-bold mt-2">Laboratórios e Unidades Didáticas</h2>
                <p class="text-gray-400 mt-2 text-sm">Infraestrutura prática integrada ao currículo técnico.</p>
            </div>
            <a href="{{ route('institutional') }}" class="flex-shrink-0 inline-flex items-center gap-2 border border-white/30 text-white text-sm font-semibold px-5 py-2.5 rounded-lg hover:bg-white/10 transition">
                Saiba mais
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            @if(isset($sectors))
                @foreach($sectors as $sector)
                <a href="{{ route('sectors.show', $sector->slug) }}"
                   class="block bg-white/5 backdrop-blur p-6 rounded-xl hover:bg-white/10 transition border border-white/10 group">
                    <div class="w-12 h-12 bg-etec-accent/20 rounded-lg flex items-center justify-center mb-4 group-hover:bg-etec-accent/30 transition">
                        @switch($sector->icon)
                            @case('cow')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6c-1 0-2 .5-2 1.5S11 9 11 10c0 2-2 3-2 5s1.5 3 3 3 3-1 3-3-2-3-2-5c0-1 1-1.5 1-2.5S13 6 12 6zM9.5 6C8 4.5 6 5 6 5M14.5 6C16 4.5 18 5 18 5"/></svg>
                                @break
                            @case('pig')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><ellipse cx="12" cy="12" rx="7" ry="5" stroke-width="1.5"/><circle cx="10" cy="11" r="1" fill="currentColor"/><circle cx="14" cy="11" r="1" fill="currentColor"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 10c-1-1-2.5-.5-3 1M18 10c1-1 2.5-.5 3 1"/></svg>
                                @break
                            @case('chicken')
                            @case('bee')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4a3 3 0 100 6 3 3 0 000-6zM6.343 7.657a8 8 0 1011.314 0M12 10v10M9 16l3 3 3-3"/></svg>
                                @break
                            @case('fish')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 8c0 0-3-4-8-4S3 8 3 12s3 8 7 8c5 0 8-4 8-4l3-4-3-4z"/><circle cx="8" cy="11" r="1" fill="currentColor"/></svg>
                                @break
                            @case('leaf')
                            @case('tree')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22V12m0 0c0 0-6-3-6-8a6 6 0 0112 0c0 5-6 8-6 8z"/></svg>
                                @break
                            @case('factory')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21V9l5-3v3l5-3v3l5-3v12H3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21v-4h3v4M14 21v-4h3v4"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 3v3M12 2v4M17 3v3"/></svg>
                                @break
                            @case('computer')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke-width="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 21h8M12 17v4"/></svg>
                                @break
                            @case('flask')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6m-5 0v6L5 19a2 2 0 002 2h10a2 2 0 002-2L14 9V3"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 15h14"/></svg>
                                @break
                            @case('cheese')
                            @case('meat')
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 2a10 10 0 110 20A10 10 0 0112 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h8M12 8v8"/></svg>
                                @break
                            @default
                                <svg class="w-6 h-6 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @endswitch
                    </div>
                    <h3 class="font-bold text-base mb-1 leading-snug">{{ $sector->name }}</h3>
                    <p class="text-xs text-gray-400 leading-relaxed line-clamp-2">{{ $sector->summary }}</p>
                    <div class="mt-4 flex items-center gap-1 text-xs text-etec-accent font-semibold opacity-0 group-hover:opacity-100 transition">
                        Ver detalhes
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endforeach
            @endif
        </div>
    </div>
</section>

{{-- CTA Banner --}}
<section class="bg-etec-medium py-16">
    <div class="container mx-auto px-4 text-center text-white">
        <h2 class="text-3xl font-bold mb-4">Venha fazer parte da Etec SAM</h2>
        <p class="text-white/80 mb-8 max-w-xl mx-auto">Inscrições abertas para o Vestibulinho do Centro Paula Souza. Educação técnica pública e gratuita.</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="https://vestibulinho.etec.sp.gov.br/home/" class="inline-flex items-center justify-center gap-2 bg-etec-accent text-etec-dark font-bold px-8 py-3.5 rounded-lg hover:bg-yellow-400 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                Inscreva-se no Vestibulinho
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 border-2 border-white/40 text-white font-semibold px-8 py-3.5 rounded-lg hover:bg-white/10 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Fale Conosco
            </a>
        </div>
    </div>
</section>

@endsection
