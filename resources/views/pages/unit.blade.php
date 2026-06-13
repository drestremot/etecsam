@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">

            <div class="flex-grow text-center md:text-left">
                <span class="inline-flex items-center gap-1.5 bg-white/10 text-etec-accent px-3 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Unidade de Ensino
                </span>
                <h1 class="text-4xl md:text-5xl font-bold mb-3 leading-tight">
                    {{ $unit->name }}
                </h1>
                <p class="text-xl text-gray-300 flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-5 h-5 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Localizada em {{ $unit->city }}
                </p>
            </div>

            @if($unit->coordinator)
            <div class="bg-white text-gray-800 p-5 rounded-2xl shadow-lg flex items-center gap-4 min-w-[300px]">
                <div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden border-2 border-etec-light flex-shrink-0">
                    @if($unit->coordinator->photo)
                        <img src="{{ photo_url($unit->coordinator->photo) }}"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'"
                             class="w-full h-full object-cover">
                        <div style="display:none" class="w-full h-full flex items-center justify-center bg-etec-dark text-white font-bold text-xl">{{ substr($unit->coordinator->name,0,1) }}</div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-etec-dark text-white font-bold text-xl">
                            {{ substr($unit->coordinator->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-bold text-etec-medium uppercase tracking-wide block mb-0.5">Coord. de Sala Descentralizada</span>
                    <strong class="text-sm block leading-tight text-gray-800">{{ $unit->coordinator->name }}</strong>
                    <a href="mailto:{{ $unit->coordinator->email }}"
                       class="text-xs text-blue-600 hover:underline truncate block max-w-[200px] mt-1">
                        {{ $unit->coordinator->email }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-2 border-l-4 border-etec-medium pl-3">
        <svg class="w-5 h-5 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
        Cursos oferecidos nesta unidade
    </h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($unit->courses as $course)
        <a href="{{ route('courses.show', $course->slug) }}"
           class="block bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:border-etec-accent transition duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-lg font-semibold group-hover:bg-etec-dark group-hover:text-white transition">
                    {{ $course->type }}
                </span>
                <div class="w-8 h-8 bg-gray-50 rounded-lg flex items-center justify-center text-gray-400 group-hover:bg-etec-accent group-hover:text-etec-dark transition">
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition leading-snug">
                {{ $course->title }}
            </h3>

            <p class="text-sm text-gray-500 line-clamp-2 mb-4 leading-relaxed">
                {{ $course->description }}
            </p>

            <div class="flex items-center gap-1.5 text-sm font-bold text-etec-dark group-hover:text-etec-accent transition">
                Ver grade e professores
                <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-14 bg-gray-50 rounded-xl border border-dashed border-gray-200">
            <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
            <p class="text-gray-500">Nenhum curso cadastrado nesta unidade ainda.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-12">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-etec-dark font-semibold transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para a Página Principal
        </a>
    </div>
</div>
@endsection
