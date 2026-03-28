@extends('layouts.app')

@section('content')

<div class="bg-etec-dark text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-3xl font-serif font-bold mb-2">Nossas Unidades e Cursos</h1>
        <p class="text-gray-300">Conheça as salas descentralizadas e a sede da Etec Sebastiana Augusta de Moraes.</p>
    </div>
</div>

<div class="container mx-auto px-4 py-12 space-y-16">

    @foreach($units as $unit)
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">

        <div class="bg-gray-50 p-6 md:p-8 border-b border-gray-200 flex flex-col md:flex-row gap-6 items-center md:items-start">

            <div class="flex-grow text-center md:text-left">
                <div class="inline-block bg-etec-accent text-etec-dark text-xs font-bold px-3 py-1 rounded uppercase mb-2">
                    {{ $unit->city }}
                </div>
                <h2 class="text-2xl md:text-3xl font-serif font-bold text-etec-dark mb-2">
                    {{ $unit->name }}
                </h2>
                <p class="text-gray-500 text-sm">
                    Confira abaixo os cursos oferecidos nesta unidade de ensino.
                </p>
            </div>

            @if($unit->coordinator)
            <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex items-center gap-4 min-w-[300px]">
                <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                    @if($unit->coordinator->photo)
                        <img src="{{ asset($unit->coordinator->photo) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-etec-medium text-white font-bold">
                            {{ substr($unit->coordinator->name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase block">Coord. de Sala</span>
                    <strong class="text-sm text-etec-dark block leading-tight">{{ $unit->coordinator->name }}</strong>
                    <span class="text-xs text-blue-600 truncate block max-w-[150px]">{{ $unit->coordinator->email }}</span>
                </div>
            </div>
            @endif
        </div>

        <div class="p-6 md:p-8 bg-white">
            <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="text-etec-medium">🎓</span> Cursos Disponíveis
            </h3>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($unit->courses as $course)
                <div class="border border-gray-200 rounded-lg p-5 hover:border-etec-medium hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-3">
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-bold">
                            {{ $course->type }}
                        </span>
                        <a href="{{ route('courses.show', $course->slug) }}" class="text-etec-dark hover:text-etec-accent">
                            ➜
                        </a>
                    </div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2 group-hover:text-etec-medium transition">
                        <a href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                    </h4>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                        {{ $course->description }}
                    </p>
                    <a href="{{ route('courses.show', $course->slug) }}" class="text-sm font-bold text-etec-dark hover:underline">
                        Ver Detalhes do Curso
                    </a>
                </div>
                @endforeach
            </div>
        </div>

    </div>
    @endforeach

</div>

@endsection
