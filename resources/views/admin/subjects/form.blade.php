@extends('admin.layouts.app')
@section('page-title', isset($subject) ? 'Editar Disciplina' : 'Nova Disciplina')

@section('header-actions')
    <a href="{{ route('admin.courses.subjects.index', $course) }}"
       class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1.5">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Voltar à Grade
    </a>
@endsection

@section('content')
<div class="max-w-2xl">

    {{-- Course context bar --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('admin.courses.index') }}" class="hover:text-indigo-600">Cursos</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('admin.courses.subjects.index', $course) }}" class="hover:text-indigo-600">{{ $course->title }}</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-800 font-medium">{{ isset($subject) ? 'Editar' : 'Nova Disciplina' }}</span>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-7">

        @if($errors->any())
            <div class="mb-5 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center gap-2 mb-1">
                    <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm font-semibold text-red-700">Corrija os erros abaixo:</p>
                </div>
                <ul class="list-disc list-inside text-xs text-red-600 space-y-0.5 pl-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @php
            $action = isset($subject)
                ? route('admin.courses.subjects.update', [$course, $subject])
                : route('admin.courses.subjects.store', $course);
        @endphp

        <form action="{{ $action }}" method="POST" class="space-y-5">
            @csrf
            @if(isset($subject)) @method('PUT') @endif

            {{-- Nome da disciplina --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nome da Disciplina <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $subject->name ?? '') }}"
                       placeholder="Ex: Bovinocultura de Corte"
                       class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('name') border-red-400 @enderror">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Professor --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Professor Responsável</label>
                <select name="teacher_id"
                        class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('teacher_id') border-red-400 @enderror">
                    <option value="">— Não atribuído —</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id', $subject->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                            @if($teacher->specialty) — {{ $teacher->specialty }} @endif
                        </option>
                    @endforeach
                </select>
                @error('teacher_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Carga horária + Semestre --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Carga Horária (horas) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="workload" min="1" max="9999"
                           value="{{ old('workload', $subject->workload ?? '') }}"
                           placeholder="Ex: 80"
                           class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('workload') border-red-400 @enderror">
                    @error('workload') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Semestre / Período</label>
                    <select name="semester"
                            class="w-full border border-gray-300 rounded-lg px-3.5 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition">
                        <option value="">— Não definido —</option>
                        @foreach(['1º Semestre', '2º Semestre', '3º Semestre', '4º Semestre', 'Anual', 'Semestral'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('semester', $subject->semester ?? '') === $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- PTD URL --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Link do PTD
                    <span class="ml-1 text-xs font-normal text-gray-400">(Plano de Trabalho Docente — URL opcional)</span>
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </span>
                    <input type="url" name="ptd_file"
                           value="{{ old('ptd_file', $subject->ptd_file ?? '') }}"
                           placeholder="https://drive.google.com/..."
                           class="w-full border border-gray-300 rounded-lg pl-10 pr-3.5 py-2.5 text-sm text-gray-900 focus:ring-2 focus:ring-indigo-300 focus:border-indigo-400 outline-none transition @error('ptd_file') border-red-400 @enderror">
                </div>
                @error('ptd_file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                <p class="text-xs text-gray-400 mt-1">Cole o link do Google Drive, SharePoint, ou outro serviço de armazenamento.</p>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between pt-2 border-t border-gray-100 mt-6">
                <a href="{{ route('admin.courses.subjects.index', $course) }}"
                   class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ isset($subject) ? 'Salvar Alterações' : 'Adicionar Disciplina' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
