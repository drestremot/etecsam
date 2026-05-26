@extends('admin.layouts.app')
@section('page-title', 'Dashboard')

@section('content')

{{-- Cards de estatísticas --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-6">

    <a href="{{ route('admin.teachers.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['teachers'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Professores</p>
        </div>
    </a>

    <a href="{{ route('admin.departments.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-purple-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['departments'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Departamentos</p>
        </div>
    </a>

    <a href="{{ route('admin.laboratories.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-teal-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3h6m-5 0v6L5 19a2 2 0 002 2h10a2 2 0 002-2L14 9V3M5 15h14"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['laboratories'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Laboratórios</p>
        </div>
    </a>

    <a href="{{ route('admin.projects.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-amber-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['projects'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Projetos</p>
        </div>
    </a>

    <a href="{{ route('admin.courses.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['courses'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Cursos</p>
        </div>
    </a>

    <a href="{{ route('admin.units.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-green-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['units'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Unidades</p>
        </div>
    </a>

    <a href="{{ route('admin.sectors.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-orange-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['sectors'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Setores</p>
        </div>
    </a>

    <a href="{{ route('admin.events.index') }}" class="bg-white rounded-2xl shadow-sm hover:shadow-md transition p-5 flex items-center gap-4 group border border-gray-100">
        <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-rose-600 group-hover:text-white transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800 leading-none">{{ $stats['events'] }}</p>
            <p class="text-xs text-gray-500 mt-0.5">Eventos</p>
        </div>
    </a>

</div>

{{-- Acesso Rápido --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="font-semibold text-gray-700 mb-4 text-sm uppercase tracking-wide">Acesso Rápido</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">

        <a href="{{ route('admin.teachers.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Novo Funcionário
        </a>

        <a href="{{ route('admin.departments.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            Novo Departamento
        </a>

        <a href="{{ route('admin.laboratories.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3h6m-5 0v6L5 19a2 2 0 002 2h10a2 2 0 002-2L14 9V3M5 15h14"/></svg>
            Novo Laboratório
        </a>

        <a href="{{ route('admin.projects.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            Novo Projeto
        </a>

        <a href="{{ route('admin.courses.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
            Novo Curso
        </a>

        <a href="{{ route('admin.events.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Novo Evento
        </a>

        <a href="{{ route('admin.documents.create') }}"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 hover:border-indigo-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Novo Documento
        </a>

        <a href="{{ route('home') }}" target="_blank"
           class="flex items-center gap-2.5 px-4 py-3 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 bg-gray-50 hover:bg-green-50 hover:text-green-700 hover:border-green-200 transition">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Ver Site
        </a>
    </div>
</div>

@endsection
