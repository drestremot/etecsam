@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
        <div>
            <h1 class="text-3xl font-bold mb-1">Nossas Unidades e Cursos</h1>
            <p class="text-gray-300">Salas descentralizadas e sede da Etec Sebastiana Augusta de Moraes.</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 space-y-14">

    @foreach($units as $unit)
    <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 overflow-hidden">

        {{-- Cabeçalho da unidade --}}
        <div class="bg-white/5 p-6 md:p-8 border-b border-white/10 flex flex-col md:flex-row gap-6 items-center md:items-start">
            <div class="flex-grow text-center md:text-left">
                <span class="inline-block bg-etec-accent text-etec-dark text-xs font-bold px-3 py-1 rounded-lg uppercase mb-2">
                    {{ $unit->city }}
                </span>
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-1 leading-tight"
                   >
                    {{ $unit->name }}
                </h2>
                <p class="text-blue-100 text-sm flex items-center gap-1.5 justify-center md:justify-start">
                    <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                    {{ $unit->courses->count() }} {{ $unit->courses->count() === 1 ? 'curso disponível' : 'cursos disponíveis' }}
                </p>
            </div>

            @if($unit->coordinator)
            <div class="bg-white/10 p-4 rounded-xl border border-white/10 flex items-center gap-4 flex-shrink-0">
                <div class="w-12 h-12 rounded-full bg-white/10 overflow-hidden flex-shrink-0 border-2 border-etec-light">
                    @if($unit->coordinator->photo)
                        <img src="{{ photo_url($unit->coordinator->photo) }}" class="w-full h-full object-cover"
                             onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                        <div class="w-full h-full hidden items-center justify-center bg-etec-medium text-white font-bold">{{ substr($unit->coordinator->name, 0, 1) }}</div>
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-etec-medium text-white font-bold">
                            {{ substr($unit->coordinator->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="min-w-0">
                    <span class="text-xs font-bold text-blue-200/70 uppercase tracking-wide block mb-0.5">Coord. de Sala</span>
                    <strong class="text-sm text-white block leading-tight">{{ $unit->coordinator->name }}</strong>
                    <a href="mailto:{{ $unit->coordinator->email }}"
                       class="text-xs text-blue-200 hover:text-etec-accent hover:underline truncate block max-w-[180px]">
                        {{ $unit->coordinator->email }}
                    </a>
                </div>
            </div>
            @endif
        </div>

        {{-- Cursos --}}
        <div class="p-6 md:p-8">
            <h3 class="font-bold text-blue-100 mb-6 flex items-center gap-2 text-sm uppercase tracking-wide">
                <svg class="w-4 h-4 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                Cursos Disponíveis
            </h3>

            @if($unit->courses->isEmpty())
                <p class="text-blue-200/70 text-sm py-6 text-center">Nenhum curso cadastrado para esta unidade.</p>
            @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($unit->courses as $course)
                <div class="border border-white/10 rounded-xl p-5 hover:border-etec-accent hover:shadow-md hover:shadow-etec-dark/30 transition group bg-white/5 hover:bg-white/10">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-white/10 text-blue-100 text-xs px-2.5 py-1 rounded-lg font-semibold">
                            {{ $course->type }}
                        </span>
                        <a href="{{ route('courses.show', $course->slug) }}"
                           class="w-7 h-7 bg-white/10 rounded-lg flex items-center justify-center text-blue-200/70 border border-white/10 hover:border-etec-accent hover:text-etec-accent transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <h4 class="text-base font-bold text-white mb-2 group-hover:text-etec-accent transition leading-snug">
                        <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                    </h4>
                    <p class="text-sm text-blue-100 line-clamp-2 mb-4 leading-relaxed">
                        {{ $course->description }}
                    </p>
                    <a href="{{ route('courses.show', $course->slug) }}"
                       class="inline-flex items-center gap-1.5 text-sm font-bold text-white hover:text-etec-accent transition">
                        Ver detalhes
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>
    @endforeach

</div>
@endsection
