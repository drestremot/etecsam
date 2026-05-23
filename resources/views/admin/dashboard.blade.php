@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
    @php
        $cards = [
            ['label' => 'Professores',   'value' => $stats['teachers'],     'icon' => '👥', 'color' => 'bg-blue-500',   'route' => 'admin.teachers.index'],
            ['label' => 'Departamentos', 'value' => $stats['departments'],  'icon' => '🏛️', 'color' => 'bg-purple-500', 'route' => 'admin.departments.index'],
            ['label' => 'Laboratórios',  'value' => $stats['laboratories'], 'icon' => '🧪', 'color' => 'bg-teal-500',   'route' => 'admin.laboratories.index'],
            ['label' => 'Projetos',      'value' => $stats['projects'],     'icon' => '🔬', 'color' => 'bg-amber-500',  'route' => 'admin.projects.index'],
            ['label' => 'Cursos',        'value' => $stats['courses'],      'icon' => '📚', 'color' => 'bg-indigo-500', 'route' => 'admin.courses.index'],
            ['label' => 'Unidades',      'value' => $stats['units'],        'icon' => '🏫', 'color' => 'bg-green-500',  'route' => 'admin.units.index'],
            ['label' => 'Setores',       'value' => $stats['sectors'],      'icon' => '🚜', 'color' => 'bg-orange-500', 'route' => 'admin.sectors.index'],
            ['label' => 'Eventos',       'value' => $stats['events'],       'icon' => '📅', 'color' => 'bg-rose-500',   'route' => 'admin.events.index'],
        ];
    @endphp

    @foreach($cards as $card)
    <a href="{{ route($card['route']) }}" class="bg-white rounded-xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4">
        <div class="{{ $card['color'] }} text-white rounded-lg w-12 h-12 flex items-center justify-center text-2xl flex-shrink-0">
            {{ $card['icon'] }}
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
        </div>
    </a>
    @endforeach
</div>

<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="font-semibold text-gray-700 mb-4">Acesso Rápido</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('admin.teachers.create') }}" class="btn-quick">+ Novo Funcionário</a>
        <a href="{{ route('admin.departments.create') }}" class="btn-quick">+ Novo Departamento</a>
        <a href="{{ route('admin.laboratories.create') }}" class="btn-quick">+ Novo Laboratório</a>
        <a href="{{ route('admin.projects.create') }}" class="btn-quick">+ Novo Projeto</a>
        <a href="{{ route('admin.courses.create') }}" class="btn-quick">+ Novo Curso</a>
        <a href="{{ route('admin.events.create') }}" class="btn-quick">+ Novo Evento</a>
        <a href="{{ route('admin.documents.create') }}" class="btn-quick">+ Novo Documento</a>
        <a href="{{ route('home') }}" target="_blank" class="btn-quick">🌐 Ver Site</a>
    </div>
</div>

<style>
    .btn-quick {
        @apply block text-center text-sm bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 border border-gray-200 hover:border-indigo-300 rounded-lg px-4 py-3 transition font-medium text-gray-700;
    }
</style>
@endsection
