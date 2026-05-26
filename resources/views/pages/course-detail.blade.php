@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <div class="flex flex-col md:flex-row gap-8 items-start">

            {{-- Ícone do curso --}}
            <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-sm border border-white/20 flex-shrink-0">
                <svg class="w-12 h-12 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>

            <div class="flex-grow">
                <div class="flex flex-wrap items-center gap-3 mb-3">
                    <span class="bg-etec-accent text-etec-dark text-xs font-bold px-3 py-1 rounded-lg uppercase">
                        {{ $course->type }}
                    </span>
                    @if($course->unit)
                    <span class="text-gray-300 text-sm flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-etec-accent flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $course->unit->name }} — {{ $course->unit->city }}
                    </span>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">
                    {{ $course->title }}
                </h1>

                <p class="text-xl text-gray-300 max-w-3xl leading-relaxed">
                    {{ $course->description }}
                </p>
            </div>

            @if($course->course_plan)
            <div class="hidden md:flex flex-col items-center gap-2 group flex-shrink-0">
                <a href="{{ $course->course_plan }}" target="_blank"
                   class="flex flex-col items-center gap-2">
                    <div class="w-16 h-16 bg-white/10 hover:bg-white/20 text-white rounded-2xl flex items-center justify-center shadow-lg transition border border-white/20">
                        <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-300 group-hover:text-etec-accent transition">Plano de Curso</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">

    <aside class="space-y-6">

        {{-- Coordenador --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
                <svg class="w-4 h-4 text-etec-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <h3 class="font-bold text-gray-800 text-sm">Coordenador do Curso</h3>
            </div>
            <div class="p-6 text-center">
                <div class="w-24 h-24 mx-auto bg-gray-100 rounded-full mb-4 overflow-hidden border-4 border-etec-light shadow-sm">
                    @if($course->technicalCoordinator && $course->technicalCoordinator->photo)
                        <img src="{{ photo_url($course->technicalCoordinator->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-etec-medium text-white text-2xl font-bold">
                            {{ substr($course->technicalCoordinator->name ?? 'C', 0, 1) }}
                        </div>
                    @endif
                </div>
                <h4 class="text-lg font-bold text-etec-dark">
                    {{ $course->technicalCoordinator->name ?? 'A definir' }}
                </h4>
                <p class="text-xs text-gray-500 uppercase font-bold tracking-wide mb-4">
                    Coordenador Técnico
                </p>
                @if($course->technicalCoordinator)
                <a href="mailto:{{ $course->technicalCoordinator->email }}"
                   class="inline-flex items-center gap-2 text-sm text-etec-medium hover:text-etec-dark font-semibold bg-etec-light/40 px-4 py-2 rounded-lg transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Fale com a Coordenação
                </a>
                @endif
            </div>
        </div>

        {{-- Horário --}}
        <div class="bg-etec-dark text-white rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 flex items-center gap-2">
                <svg class="w-4 h-4 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h3 class="font-bold text-base">Horário das Aulas</h3>
            </div>
            <div class="p-6 text-sm leading-relaxed text-gray-300 whitespace-pre-line">
                @if($course->schedule)
                    {{ $course->schedule }}
                @else
                    <span class="text-gray-400">Horário não cadastrado.</span>
                    <br>
                    <span class="text-xs text-gray-500 mt-2 block">Segunda a Sexta: 19h00 às 23h00</span>
                @endif
            </div>
        </div>
    </aside>

    {{-- Grade curricular --}}
    <div class="lg:col-span-2">
        <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-l-4 border-etec-accent pl-3">
            <svg class="w-5 h-5 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            Grade Curricular e Docentes
        </h2>

        <div class="space-y-4">
            @forelse($course->subjects as $subject)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
                <div class="flex flex-col md:flex-row gap-6 items-start">

                    {{-- Docente --}}
                    <div class="flex items-start gap-4 md:w-1/3 md:border-r border-gray-100 md:pr-6 flex-shrink-0">
                        <div class="w-14 h-14 rounded-full bg-gray-100 overflow-hidden flex-shrink-0 border-2 border-gray-50">
                            @if($subject->teacher && $subject->teacher->photo)
                                <img src="{{ photo_url($subject->teacher->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-500">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <span class="text-xs text-gray-400 uppercase font-bold block mb-0.5">Docente</span>
                            <div class="font-bold text-gray-800 text-sm leading-tight">
                                {{ $subject->teacher->name ?? 'A atribuir' }}
                            </div>
                            @if($subject->teacher && $subject->teacher->specialty)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                {{ $subject->teacher->specialty }}
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Disciplina --}}
                    <div class="flex-grow min-w-0">
                        <h4 class="text-lg font-bold text-etec-dark mb-1.5 leading-snug">
                            {{ $subject->name }}
                        </h4>
                        <div class="flex flex-wrap gap-3 text-xs text-gray-500 mb-4">
                            <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $subject->workload }} horas
                            </span>
                            <span class="inline-flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Semestral
                            </span>
                        </div>

                        @if($subject->ptd_file)
                        <a href="{{ $subject->ptd_file }}" target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-bold rounded-lg hover:bg-green-100 transition border border-green-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Ver PTD (Plano de Trabalho)
                        </a>
                        @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs font-semibold rounded-lg border border-gray-100 cursor-not-allowed">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            PTD em análise
                        </span>
                        @endif
                    </div>

                </div>
            </div>
            @empty
            <div class="py-14 text-center bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                <p class="text-gray-500">Nenhuma disciplina cadastrada para este curso ainda.</p>
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
