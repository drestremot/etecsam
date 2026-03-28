@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">

            <div class="flex-grow text-center md:text-left">
                <span class="bg-white/10 text-etec-accent px-3 py-1 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 mb-4 inline-block">
                    Unidade de Ensino
                </span>
                <h1 class="text-4xl md:text-5xl font-serif font-bold mb-2">
                    {{ $unit->name }}
                </h1>
                <p class="text-xl text-gray-300 flex items-center justify-center md:justify-start gap-2">
                    📍 Localizada em {{ $unit->city }}
                </p>
            </div>

            @if($unit->coordinator)
            <div class="bg-white text-gray-800 p-4 rounded-xl shadow-lg flex items-center gap-4 min-w-[300px]">
                <div class="w-16 h-16 rounded-full bg-gray-200 overflow-hidden border-2 border-etec-medium">
                    @if($unit->coordinator->photo)
                        <img src="{{ asset($unit->coordinator->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-etec-dark text-white font-bold text-xl">
                            {{ substr($unit->coordinator->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs font-bold text-etec-medium uppercase block">Coord. de Sala Descentralizada</span>
                    <strong class="text-sm block leading-tight">{{ $unit->coordinator->name }}</strong>
                    <a href="mailto:{{ $unit->coordinator->email }}" class="text-xs text-blue-600 hover:underline truncate block max-w-[180px]">
                        {{ $unit->coordinator->email }}
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-8 flex items-center gap-2 border-l-4 border-etec-medium pl-3">
        🎓 Cursos oferecidos nesta unidade
    </h2>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($unit->courses as $course)
        <a href="{{ route('courses.show', $course->slug) }}" class="block bg-white rounded-lg shadow-sm border border-gray-200 p-6 hover:shadow-xl hover:border-etec-accent transition duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold group-hover:bg-etec-dark group-hover:text-white transition">
                    {{ $course->type }}
                </span>
                <span class="text-etec-medium text-2xl group-hover:scale-110 transition">➜</span>
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition">
                {{ $course->title }}
            </h3>

            <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                {{ $course->description }}
            </p>

            <div class="text-sm font-bold text-etec-dark flex items-center gap-1">
                Ver Grade e Professores
            </div>
        </a>
        @empty
        <div class="col-span-3 text-center py-12 bg-gray-50 rounded-lg border border-dashed border-gray-300">
            <p class="text-gray-500 text-lg">Nenhum curso cadastrado nesta unidade ainda.</p>
        </div>
        @endforelse
    </div>

    <div class="mt-12">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-etec-dark flex items-center gap-2 font-bold">
            ← Voltar para a Página Principal
        </a>
    </div>
</div>
@endsection
