@extends('layouts.app')

@section('content')

{{-- Header do Curso --}}
<div class="bg-gradient-to-r from-[#0c1b33] via-[#14284b] to-[#0c1b33] text-white py-14 relative overflow-hidden border-b border-white/10 shadow-md" style="background-color: #0c1b33; color: #ffffff;">
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row gap-8 items-start justify-between">

            <div class="flex items-start gap-6">
                {{-- Ícone do curso --}}
                <div class="w-16 h-16 rounded-3xl bg-amber-400/20 border border-amber-400/30 flex items-center justify-center flex-shrink-0 text-amber-300 shadow-md">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>

                <div class="space-y-3">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="bg-amber-400 text-slate-950 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                            {{ $course->type }}
                        </span>
                        @if($course->unit)
                        <span class="text-slate-200 text-xs sm:text-sm flex items-center gap-1.5 font-medium">
                            <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $course->unit->name }} &bull; {{ $course->unit->city }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white">
                        {{ $course->title }}
                    </h1>

                    <p class="text-sm sm:text-base text-slate-200 max-w-3xl leading-relaxed font-normal">
                        {{ $course->description }}
                    </p>
                </div>
            </div>

            @if($course->course_plan)
            <div class="hidden md:flex flex-col items-center gap-2 flex-shrink-0">
                <a href="{{ $course->course_plan }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-md border border-white/25 text-white text-xs font-bold px-5 py-3 rounded-2xl transition shadow-sm">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Plano de Curso Oficial (PDF)</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-[#0b172a] text-white py-14" style="background-color: #0b172a; color: #ffffff;">
    <div class="container mx-auto px-4 grid lg:grid-cols-3 gap-10">

        {{-- Coluna Lateral: Coordenação e Horários --}}
        <aside class="space-y-6">

            {{-- Coordenadores --}}
            @php
                $allCoordinators = $course->coordinators;
                $hasTecnico = $allCoordinators->where('pivot.role', 'tecnico')->count() > 0;
                $hasDesc    = $allCoordinators->where('pivot.role', 'descentralizado')->count() > 0;
            @endphp
            @if($allCoordinators->count() > 0)
            <div class="bg-[#14284b] rounded-3xl shadow-sm border border-white/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-[#0f223f] flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <h3 class="font-extrabold text-white text-xs uppercase tracking-wider">Coordenação do Curso</h3>
                </div>
                <div class="divide-y divide-white/10">
                    @if($hasTecnico)
                        @foreach($allCoordinators->where('pivot.role', 'tecnico') as $coord)
                        <div class="p-5 flex items-center gap-4">
                            <x-avatar :name="$coord->name" :photo="$coord->photo" :size="55" class="border-2 border-amber-400/30" />
                            <div class="min-w-0">
                                <p class="font-bold text-white text-sm leading-snug truncate">{{ $coord->name }}</p>
                                @if($coord->email)
                                <a href="mailto:{{ $coord->email }}" class="text-xs text-amber-400 hover:underline mt-0.5 block truncate">{{ $coord->email }}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif

                    @if($hasDesc)
                        @foreach($allCoordinators->where('pivot.role', 'descentralizado') as $coord)
                        <div class="p-5 flex items-center gap-4">
                            <x-avatar :name="$coord->name" :photo="$coord->photo" :size="55" class="border-2 border-amber-400/30" />
                            <div class="min-w-0">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Sala Descentralizada</span>
                                <p class="font-bold text-white text-sm leading-snug truncate">{{ $coord->name }}</p>
                                @if($coord->email)
                                <a href="mailto:{{ $coord->email }}" class="text-xs text-amber-400 hover:underline mt-0.5 block truncate">{{ $coord->email }}</a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>
            @endif

            {{-- Horário das Aulas --}}
            <div class="bg-[#14284b] rounded-3xl shadow-sm border border-white/10 overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-[#0f223f] flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <h3 class="font-extrabold text-white text-xs uppercase tracking-wider">Turno & Horários</h3>
                </div>
                <div class="p-6 text-xs sm:text-sm text-slate-300 leading-relaxed">
                    @if($course->schedule)
                        <div class="whitespace-pre-line">{{ $course->schedule }}</div>
                    @else
                        <p class="text-slate-400">Horário regular:</p>
                        <strong class="text-white block mt-1">Segunda a Sexta-feira &bull; Período Noturno</strong>
                        <span class="text-xs text-slate-400 mt-1 block">Das 19h00 às 23h00</span>
                    @endif
                </div>
            </div>

            {{-- Inscrições CTA --}}
            <div class="bg-gradient-to-br from-amber-400 to-amber-500 rounded-3xl p-6 text-slate-950 shadow-lg text-center space-y-3">
                <h4 class="font-extrabold text-base">Interessado neste Curso?</h4>
                <p class="text-xs text-slate-900/80 leading-relaxed font-medium">As inscrições ocorrem semestralmente através do Vestibulinho do Centro Paula Souza.</p>
                <a href="https://vestibulinho.etec.sp.gov.br/home/" target="_blank" rel="noopener noreferrer"
                   class="inline-block w-full bg-slate-950 text-white font-bold text-xs py-3 rounded-2xl shadow-md hover:bg-slate-900 transition">
                    Acessar Vestibulinho
                </a>
            </div>
        </aside>

        {{-- Grade Curricular & Docentes --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="flex items-center justify-between border-b border-white/10 pb-4">
                <h2 class="text-2xl font-extrabold text-white flex items-center gap-2.5">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                    <span>Matriz Curricular & Docentes</span>
                </h2>
                <span class="text-xs font-semibold text-slate-400">
                    {{ $course->subjects->count() }} {{ $course->subjects->count() === 1 ? 'disciplina' : 'disciplinas' }}
                </span>
            </div>

            <div class="space-y-4">
                @forelse($course->subjects as $subject)
                <div class="bg-[#14284b] hover:bg-[#1a335f] rounded-3xl shadow-sm border border-white/10 p-6 transition-all duration-300">
                    <div class="flex flex-col md:flex-row gap-6 items-start">

                        {{-- Docente --}}
                        <div class="flex items-start gap-4 md:w-5/12 md:border-r border-white/10 md:pr-6 flex-shrink-0">
                            <x-avatar :name="$subject->teacher?->name ?? 'Docente'" :photo="$subject->teacher?->photo" :size="56" class="border-2 border-amber-400/30" />
                            <div class="min-w-0">
                                <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest block mb-0.5">Professor(a)</span>
                                <div class="font-bold text-white text-sm leading-tight truncate">
                                    {{ $subject->teacher->name ?? 'A atribuir' }}
                                </div>
                                @if($subject->teacher && $subject->teacher->specialty)
                                <p class="text-xs text-slate-300 mt-0.5 line-clamp-1">
                                    {{ $subject->teacher->specialty }}
                                </p>
                                @endif
                            </div>
                        </div>

                        {{-- Disciplina --}}
                        <div class="flex-grow min-w-0 space-y-2.5">
                            <h4 class="text-base font-bold text-white leading-snug">
                                {{ $subject->name }}
                            </h4>
                            
                            <div class="flex flex-wrap gap-2 text-xs">
                                <span class="inline-flex items-center gap-1.5 bg-[#0f223f] text-slate-200 px-2.5 py-1 rounded-lg font-medium border border-white/10">
                                    <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $subject->workload }} horas</span>
                                </span>
                                <span class="inline-flex items-center gap-1.5 bg-[#0f223f] text-slate-200 px-2.5 py-1 rounded-lg font-medium border border-white/10">
                                    Regular
                                </span>
                            </div>

                            @if($subject->ptd_file)
                            <div class="pt-2">
                                <a href="{{ $subject->ptd_file }}" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-400/15 text-amber-300 text-xs font-bold rounded-xl hover:bg-amber-400/25 transition border border-amber-400/30">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Ver Plano de Trabalho Docente (PTD)</span>
                                </a>
                            </div>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="py-16 text-center bg-[#14284b] rounded-3xl border border-dashed border-white/15">
                    <svg class="w-10 h-10 text-slate-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-slate-300 text-sm font-semibold">Nenhuma disciplina cadastrada para este curso ainda.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
