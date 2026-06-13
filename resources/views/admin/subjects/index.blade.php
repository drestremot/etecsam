@extends('admin.layouts.app')
@section('page-title', 'Grade Curricular — ' . $course->title)

@section('header-actions')
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.courses.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar aos Cursos
        </a>
        <a href="{{ route('admin.courses.subjects.create', $course) }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Disciplina
        </a>
    </div>
@endsection

@section('content')

{{-- Course info banner --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <div class="flex flex-wrap items-center gap-6">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-0.5">Curso</p>
            <p class="text-lg font-bold text-gray-800">{{ $course->title }}</p>
        </div>
        @if($course->type)
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-0.5">Modalidade</p>
            <p class="text-sm text-gray-600">{{ $course->type }}</p>
        </div>
        @endif
        @if($course->unit)
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-0.5">Unidade</p>
            <p class="text-sm text-gray-600">{{ $course->unit->name }}</p>
        </div>
        @endif
        <div class="ml-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $course->is_active ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                {{ $course->is_active ? 'Ativo' : 'Inativo' }}
            </span>
        </div>
    </div>
</div>

{{-- Subjects table --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3">
        <svg class="w-5 h-5 text-indigo-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
        <h2 class="font-semibold text-gray-800 text-sm">
            Disciplinas <span class="ml-1 text-xs font-normal text-gray-400">({{ $subjects->count() }} cadastradas)</span>
        </h2>
        <div class="ml-auto flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="q" @input="search()" type="text" placeholder="Buscar disciplina, professor..."
                   class="text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400 w-48">
            <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">✕</button>
        </div>
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th @click="sort('disc')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none tracking-wider">
                    Disciplina <span class="ml-1 text-gray-400" x-text="icon('disc')"></span>
                </th>
                <th @click="sort('prof')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none tracking-wider">
                    Professor <span class="ml-1 text-gray-400" x-text="icon('prof')"></span>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">C.H. (h)</th>
                <th @click="sort('sem')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none tracking-wider">
                    Semestre <span class="ml-1 text-gray-400" x-text="icon('sem')"></span>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">PTD</th>
                <th class="px-4 py-3 w-20"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($subjects as $subject)
            <tr class="hover:bg-gray-50"
                data-row="{{ strtolower($subject->name . ' ' . ($subject->teacher?->name ?? '')) }}"
                data-active="1"
                data-disc="{{ strtolower($subject->name) }}"
                data-prof="{{ strtolower($subject->teacher?->name ?? '') }}"
                data-sem="{{ $subject->semester ?? '' }}">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $subject->name }}</td>
                <td class="px-4 py-3 text-gray-600">
                    @if($subject->teacher)
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                {{ strtoupper(substr($subject->teacher->name, 0, 1)) }}
                            </div>
                            {{ $subject->teacher->name }}
                        </div>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-50 text-blue-700 text-xs font-semibold">
                        {{ $subject->workload }}h
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $subject->semester ?: '—' }}</td>
                <td class="px-4 py-3">
                    @if($subject->ptd_file)
                        <a href="{{ $subject->ptd_file }}" target="_blank"
                           class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:underline">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Ver PTD
                        </a>
                    @else
                        <span class="text-gray-300 text-xs">—</span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.courses.subjects.edit', [$course, $subject]) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.courses.subjects.destroy', [$course, $subject]) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Remover \'{{ addslashes($subject->name) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Excluir"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <p class="font-medium">Nenhuma disciplina cadastrada.</p>
                    <a href="{{ route('admin.courses.subjects.create', $course) }}" class="text-indigo-500 hover:underline text-sm mt-1 inline-block">Adicionar a primeira disciplina</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@include('admin.partials.table-script')
@endsection
