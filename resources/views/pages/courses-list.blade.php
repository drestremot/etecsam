@extends('layouts.app')

@section('content')

<x-page-header compact title="Nossas Unidades e Cursos" subtitle="Salas descentralizadas e sede da Etec Sebastiana Augusta de Moraes.">
    <x-slot:icon>
        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>
    </x-slot:icon>
</x-page-header>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4 space-y-12">

        @foreach($units as $unit)
        <div class="bg-[#0f223f] rounded-3xl shadow-md border border-white/10 overflow-hidden transition duration-300 hover:shadow-xl">

            {{-- Cabeçalho da unidade --}}
            <div class="bg-[#14284b] p-6 md:p-8 border-b border-white/10 flex flex-col md:flex-row gap-6 items-center md:items-start justify-between">
                <div class="flex-grow text-center md:text-left">
                    <span class="inline-block bg-amber-400/15 text-amber-300 text-xs font-bold px-3 py-1 rounded-full uppercase mb-2 border border-amber-400/25">
                        {{ $unit->city }}
                    </span>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-white mb-1 leading-tight">
                        {{ $unit->name }}
                    </h2>
                    <p class="text-slate-300 text-sm flex items-center gap-1.5 justify-center md:justify-start">
                        <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                        <span>{{ $unit->courses->count() }} {{ $unit->courses->count() === 1 ? 'curso disponível' : 'cursos disponíveis' }}</span>
                    </p>
                </div>

                @if($unit->coordinator)
                <div class="bg-[#0f223f] p-4 rounded-2xl border border-white/10 flex items-center gap-4 flex-shrink-0 shadow-sm">
                    <x-avatar :name="$unit->coordinator->name" :photo="$unit->coordinator->photo" :size="55" bg="bg-amber-400/20" text-size="text-base" class="border-2 border-amber-400/30" />
                    <div class="min-w-0">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wide block mb-0.5">Coordenação de Sala</span>
                        <strong class="text-sm text-white block leading-tight truncate max-w-[200px]">{{ $unit->coordinator->name }}</strong>
                        <a href="mailto:{{ $unit->coordinator->email }}"
                           class="text-xs text-amber-400 hover:text-amber-300 hover:underline truncate block max-w-[200px]">
                            {{ $unit->coordinator->email }}
                        </a>
                    </div>
                </div>
                @endif
            </div>

            {{-- Cursos --}}
            <div class="p-6 md:p-8 bg-[#0f223f]">
                <h3 class="font-bold text-white mb-6 flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-amber-300">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                    <span>Cursos Ofertados</span>
                </h3>

                @if($unit->courses->isEmpty())
                    <p class="text-slate-400 text-sm py-6 text-center">Nenhum curso cadastrado para esta unidade no momento.</p>
                @else
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($unit->courses as $course)
                    <div class="border border-white/10 rounded-2xl p-5 hover:border-amber-400/50 hover:shadow-xl transition-all duration-300 group bg-[#14284b] hover:bg-[#1a335f] flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <span class="bg-white/10 text-amber-300 text-xs px-2.5 py-1 rounded-lg font-semibold border border-white/10">
                                    {{ $course->type }}
                                </span>
                                <a href="{{ route('courses.show', $course->slug) }}"
                                   class="w-8 h-8 bg-white/10 rounded-xl flex items-center justify-center text-slate-300 border border-white/10 group-hover:border-amber-400 group-hover:text-amber-400 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            <h4 class="text-base font-bold text-white mb-2 group-hover:text-amber-300 transition leading-snug">
                                <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                            </h4>
                            <p class="text-xs text-slate-300 line-clamp-2 mb-4 leading-relaxed">
                                {{ $course->description }}
                            </p>
                        </div>
                        <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                            <span class="text-[11px] font-semibold text-slate-400">100% Gratuito</span>
                            <a href="{{ route('courses.show', $course->slug) }}"
                               class="inline-flex items-center gap-1 text-xs font-bold text-amber-400 group-hover:text-amber-300 group-hover:translate-x-0.5 transition">
                                <span>Ver Matriz & Detalhes</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>
        @endforeach

    </div>
</div>
@endsection
