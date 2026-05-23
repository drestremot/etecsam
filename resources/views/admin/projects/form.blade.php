@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Projeto' : 'Editar: ' . $project->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.projects.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.projects.store') : route('admin.projects.update', $project) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identificação</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nome do Projeto <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $project->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Nome do projeto">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Responsável</label>
                        <select name="responsible_id"
                                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                            <option value="">— Selecione —</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('responsible_id', $project->responsible_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Departamento</label>
                        <select name="department_id"
                                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                            <option value="">— Nenhum —</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ old('department_id', $project->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['ativo' => ['label' => 'Ativo', 'color' => 'green'], 'concluido' => ['label' => 'Concluído', 'color' => 'blue'], 'pausado' => ['label' => 'Pausado', 'color' => 'yellow']] as $val => $opt)
                        <label class="flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition
                            {{ old('status', $project->status ?? 'ativo') === $val ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-gray-300' }}">
                            <input type="radio" name="status" value="{{ $val }}"
                                   {{ old('status', $project->status ?? 'ativo') === $val ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm font-medium text-gray-700">{{ $opt['label'] }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de Início</label>
                        <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de Encerramento</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Conteúdo</h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Descreva o projeto, seu contexto e importância...">{{ old('description', $project->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Objetivos</label>
                    <textarea name="objectives" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Liste os objetivos do projeto...">{{ old('objectives', $project->objectives) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Imagem</label>
                    @if($action === 'edit' && $project->image)
                        <div class="flex items-center gap-4 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <img src="{{ Storage::url($project->image) }}" class="w-20 h-14 rounded-lg object-cover border border-gray-200">
                            <p class="text-xs text-gray-400">Selecione um novo arquivo para substituir</p>
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    <p class="mt-2 text-xs text-gray-400">JPG, PNG ou WebP — máx. 4 MB</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $project->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span>
                    <span class="text-sm font-semibold text-gray-700">Projeto visível no site</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Aparece na página pública de projetos</span>
                </span>
            </label>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Projeto' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.projects.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
