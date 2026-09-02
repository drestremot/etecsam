@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-gray-50 px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <!-- Top Navigation -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-gray-300 bg-white px-3.5 py-2 text-xs font-bold text-gray-700 shadow-sm transition hover:bg-gray-50">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Voltar ao Kanban
            </a>
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Nova Demanda Técnica</span>
        </div>

        @if($errors->any())
            <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm font-semibold text-red-800">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white p-5 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-100 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </span>
                <span>Nova Atividade</span>
            </h1>

            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-4 sm:space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Título da Atividade <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Ex: Manutenção preventiva dos computadores do Lab 02" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Departamento <span class="text-red-500">*</span></label>
                        <select name="department_id" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                            <option value="">Selecione um departamento</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Curso (Opcional)</label>
                        <select name="course_id" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="">Nenhum / Geral</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Prioridade <span class="text-red-500">*</span></label>
                        <select name="priority" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                            <option value="baixa" {{ old('priority') === 'baixa' ? 'selected' : '' }}>Baixa</option>
                            <option value="media" {{ old('priority', 'media') === 'media' ? 'selected' : '' }}>Média</option>
                            <option value="alta" {{ old('priority') === 'alta' ? 'selected' : '' }}>Alta</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Prazo Limite</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Responsável (Supervisor) <span class="text-red-500">*</span></label>
                        <select name="responsible_id" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                            <option value="">Selecione o responsável</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('responsible_id', Auth::id()) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Atribuído a (Executor) <span class="text-red-500">*</span></label>
                        <select name="assigned_to" class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>
                            <option value="">Selecione o executor</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Descrição Detalhada <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" placeholder="Descreva os detalhes da tarefa, instruções e requisitos necessários..." class="w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300" required>{{ old('description') }}</textarea>
                </div>

                <div class="mt-6 flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 pt-4 border-t border-gray-100">
                    <a href="{{ route('tasks.index') }}" class="text-center rounded-xl bg-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-300">
                        Cancelar
                    </a>
                    <button type="submit" class="text-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-indigo-500">
                        Salvar Atividade
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
