@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1200px] mx-auto space-y-6">

        <!-- Top Header & Breadcrumbs -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs sm:text-sm font-bold text-gray-500 mb-1">
                    <a href="{{ route('admin.courses.index') }}" class="hover:text-indigo-600 transition">Cursos Técnicos</a>
                    <span>/</span>
                    <a href="{{ route('admin.courses.subjects.index', $course) }}" class="hover:text-indigo-600 transition">{{ $course->title }}</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">{{ isset($subject) ? 'Editar Disciplina' : 'Nova Disciplina' }}</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    {{ isset($subject) ? 'Editar Disciplina' : 'Cadastrar Nova Disciplina' }}
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 font-medium mt-0.5">
                    Definição de professor responsável, carga horária e plano de ensino para {{ $course->title }}
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.courses.subjects.index', $course) }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar à Grade</span>
                </a>
            </div>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="rounded-2xl bg-red-600 text-white p-4 text-xs font-bold shadow-md">
                <p class="font-semibold mb-1">Por favor, corrija os erros abaixo:</p>
                <ul class="list-disc list-inside space-y-0.5 font-medium">
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

        <form action="{{ $action }}" method="POST" class="space-y-6">
            @csrf
            @if(isset($subject)) @method('PUT') @endif

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
                <h2 class="text-sm font-bold tracking-wide text-gray-800 border-b border-gray-100 pb-3">
                    Informações da Disciplina & Docente
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Nome da Disciplina <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $subject->name ?? '') }}" required
                               placeholder="Ex: Desenvolvimento de Software I, Bovinocultura de Leite..."
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Professor Responsável (Docente da Matéria)
                        </label>
                        <select name="teacher_id"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">— Não atribuído / A definir —</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $subject->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }}{{ $teacher->specialty ? ' — '.$teacher->specialty : '' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-gray-400 mt-1">O professor associado constará na grade oficial e poderá gerenciar materiais.</p>
                        @error('teacher_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Semestre / Módulo / Período
                        </label>
                        <select name="semester"
                                class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                            <option value="">— Não definido —</option>
                            @foreach(['1º Semestre', '2º Semestre', '3º Semestre', '4º Semestre', '1º Ano / Série', '2º Ano / Série', '3º Ano / Série', 'Anual', 'Semestral'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ old('semester', $subject->semester ?? '') === $opt ? 'selected' : '' }}>
                                    {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Carga Horária (Horas) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="workload" min="1" max="9999" required
                               value="{{ old('workload', $subject->workload ?? 80) }}"
                               placeholder="Ex: 80"
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        @error('workload') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Link do PTD (Plano de Trabalho Docente)
                        </label>
                        <input type="url" name="ptd_file"
                               value="{{ old('ptd_file', $subject->ptd_file ?? '') }}"
                               placeholder="https://drive.google.com/..."
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                        <p class="text-[11px] text-gray-400 mt-1">Cole o link público do Google Drive ou PDF do plano.</p>
                        @error('ptd_file') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100 mt-6">
                    <a href="{{ route('admin.courses.subjects.index', $course) }}"
                       class="px-4 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs sm:text-sm font-bold px-6 py-2.5 shadow-sm transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>{{ isset($subject) ? 'Salvar Alterações' : 'Cadastrar Disciplina & Salvar' }}</span>
                    </button>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
