@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 relative overflow-hidden">
    <div class="absolute inset-0 bg-pattern opacity-10"></div> <div class="container mx-auto px-4 relative z-10">

        <div class="flex flex-col md:flex-row gap-8 items-start">
            <div class="bg-white/10 p-6 rounded-2xl backdrop-blur-sm border border-white/20">
                <span class="text-6xl">🎓</span>
            </div>

            <div class="flex-grow">
                <div class="flex items-center gap-3 mb-2">
                    <span class="bg-etec-accent text-etec-dark text-xs font-bold px-3 py-1 rounded uppercase">
                        {{ $course->type }}
                    </span>
                    @if($course->unit)
                    <span class="text-gray-300 text-sm flex items-center gap-1">
                        📍 {{ $course->unit->name }} ({{ $course->unit->city }})
                    </span>
                    @endif
                </div>

                <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4 leading-tight">
                    {{ $course->title }}
                </h1>

                <p class="text-xl text-gray-300 max-w-3xl font-light">
                    {{ $course->description }}
                </p>
            </div>

            @if($course->course_plan)
            <div class="hidden md:block">
                <a href="{{ $course->course_plan }}" target="_blank" class="flex flex-col items-center gap-2 group">
                    <div class="w-16 h-16 bg-white text-etec-dark rounded-xl flex items-center justify-center text-3xl shadow-lg group-hover:scale-110 transition">
                        📄
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider group-hover:text-etec-accent">Plano de Curso</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 grid lg:grid-cols-3 gap-10">

    <aside class="space-y-8">

        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    👔 Coordenador do Curso
                </h3>
            </div>
            <div class="p-6 text-center">
                <div class="w-24 h-24 mx-auto bg-gray-200 rounded-full mb-4 overflow-hidden border-4 border-etec-light shadow-sm">
                    @if($course->technicalCoordinator && $course->technicalCoordinator->photo)
                        <img src="{{ asset($course->technicalCoordinator->photo) }}" class="w-full h-full object-cover">
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
                <a href="mailto:{{ $course->technicalCoordinator->email }}" class="inline-flex items-center gap-2 text-sm text-blue-600 hover:underline bg-blue-50 px-4 py-2 rounded-full">
                    ✉️ Fale com a Coordenação
                </a>
                @endif
            </div>
        </div>

        <div class="bg-etec-dark text-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-white/10 bg-black/20">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    ⏰ Horário das Aulas
                </h3>
            </div>
            <div class="p-6 text-sm leading-relaxed text-gray-300 whitespace-pre-line">
                @if($course->schedule)
                    {{ $course->schedule }}
                @else
                    Horário não cadastrado.
                    <br>
                    <span class="text-xs opacity-50">Segunda a Sexta: 19h00 às 23h00</span>
                @endif
            </div>
        </div>

    </aside>

    <div class="lg:col-span-2">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2 border-l-4 border-etec-accent pl-3">
            📚 Grade Curricular e Docentes
        </h2>

        <div class="space-y-4">
            @forelse($course->subjects as $subject)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 hover:shadow-md transition duration-300">

                <div class="flex flex-col md:flex-row gap-6 items-start">

                    <div class="flex-shrink-0 flex items-start gap-4 md:w-1/3 border-r-0 md:border-r border-gray-100 pr-0 md:pr-4">
                        <div class="w-14 h-14 rounded-full bg-gray-100 overflow-hidden flex-shrink-0">
                            @if($subject->teacher && $subject->teacher->photo)
                                <img src="{{ asset($subject->teacher->photo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-300 text-gray-500 text-xs">👤</div>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs text-gray-400 uppercase font-bold block mb-1">Docente</span>
                            <div class="font-bold text-gray-800 text-sm">
                                {{ $subject->teacher->name ?? 'A atribuir' }}
                            </div>
                            @if($subject->teacher)
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2" title="{{ $subject->teacher->bio }}">
                                {{ $subject->teacher->specialty ?? 'Especialista na área' }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex-grow">
                        <h4 class="text-lg font-bold text-etec-dark mb-1">
                            {{ $subject->name }}
                        </h4>
                        <div class="text-xs text-gray-500 mb-4 flex gap-4">
                            <span class="flex items-center gap-1">⏱️ {{ $subject->workload }} horas</span>
                            <span class="flex items-center gap-1">📅 Semestral</span>
                        </div>

                        <div class="flex gap-3 mt-auto">
                            @if($subject->ptd_file)
                            <a href="{{ $subject->ptd_file }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-green-50 text-green-700 text-xs font-bold rounded hover:bg-green-100 transition border border-green-200">
                                📋 Ver PTD (Plano de Trabalho)
                            </a>
                            @else
                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gray-50 text-gray-400 text-xs font-bold rounded border border-gray-100 cursor-not-allowed">
                                📋 PTD em análise
                            </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="p-8 text-center bg-gray-50 rounded border border-dashed border-gray-300 text-gray-500">
                Nenhuma disciplina cadastrada para este curso ainda.
            </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
