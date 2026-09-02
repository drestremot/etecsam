@extends('layouts.app')

@section('content')

<div class="bg-gradient-to-r from-[#0c1b33] via-[#14284b] to-[#0c1b33] text-white py-14 border-b border-white/10 shadow-md" style="background-color: #0c1b33; color: #ffffff;">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start justify-between">

            <div class="flex-grow text-center md:text-left space-y-3">
                <span class="inline-flex items-center gap-1.5 bg-amber-400/15 text-amber-300 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-widest border border-amber-400/25">
                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Polo de Ensino Oficial
                </span>
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white">
                    {{ $unit->name }}
                </h1>
                <p class="text-sm sm:text-base text-slate-200 flex items-center justify-center md:justify-start gap-2">
                    <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Localizada em <strong class="text-white">{{ $unit->city }}</strong></span>
                </p>
            </div>

            @if($unit->coordinator)
            <div class="bg-white/10 backdrop-blur-md text-white p-5 rounded-3xl shadow-xl border border-white/15 flex items-center gap-4 min-w-[300px]">
                <x-avatar :name="$unit->coordinator->name" :photo="$unit->coordinator->photo" :size="65" class="border-2 border-amber-400/30" />
                <div class="min-w-0">
                    <span class="text-[10px] font-bold text-amber-300 uppercase tracking-widest block mb-0.5">Coordenação de Polo</span>
                    <strong class="text-sm block leading-tight text-white font-bold truncate max-w-[200px]">{{ $unit->coordinator->name }}</strong>
                    <a href="mailto:{{ $unit->coordinator->email }}"
                       class="text-xs text-slate-200 hover:text-amber-300 hover:underline truncate block max-w-[200px] mt-1">
                        {{ $unit->coordinator->email }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between mb-8 border-b border-white/10 pb-4">
            <h2 class="text-2xl font-extrabold text-white flex items-center gap-2.5">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span>Cursos Ofertados nesta Unidade</span>
            </h2>
            <span class="text-xs font-semibold text-slate-400">
                {{ $unit->courses->count() }} {{ $unit->courses->count() === 1 ? 'curso' : 'cursos' }}
            </span>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($unit->courses as $course)
            <a href="{{ route('courses.show', $course->slug) }}"
               class="block bg-[#14284b] hover:bg-[#1a335f] rounded-3xl shadow-sm border border-white/10 p-6 hover:shadow-xl hover:-translate-y-1.5 hover:border-amber-400/50 transition-all duration-300 group flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        <span class="bg-amber-400/15 text-amber-300 text-xs px-3 py-1 rounded-full font-bold border border-amber-400/25">
                            {{ $course->type }}
                        </span>
                        <div class="w-8 h-8 rounded-xl bg-white/10 flex items-center justify-center text-slate-400 group-hover:text-amber-300 transition">
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </div>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-2 group-hover:text-amber-300 transition leading-snug">
                        {{ $course->title }}
                    </h3>

                    <p class="text-xs sm:text-sm text-slate-300 line-clamp-3 mb-4 leading-relaxed">
                        {{ $course->description }}
                    </p>
                </div>

                <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs font-bold text-amber-400 group-hover:text-amber-300">
                    <span>Ver Matriz Curricular</span>
                    <span class="group-hover:translate-x-1 transition duration-200">&rarr;</span>
                </div>
            </a>
            @empty
            <div class="col-span-3 text-center py-16 bg-[#14284b] rounded-3xl border border-dashed border-white/15">
                <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                <p class="text-slate-300 text-sm font-semibold">Nenhum curso cadastrado nesta unidade ainda.</p>
            </div>
            @endforelse
        </div>

        <div class="mt-14 pt-6 border-t border-white/10">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-slate-300 hover:text-white font-semibold transition text-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                <span>Voltar para a Página Inicial</span>
            </a>
        </div>
    </div>
</div>
@endsection
