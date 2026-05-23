@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Projeto' : 'Editar: ' . $project->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.projects.store') : route('admin.projects.update', $project) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Nome do Projeto *</label>
                <input type="text" name="name" value="{{ old('name', $project->name) }}" required class="input">
            </div>
            <div>
                <label class="label">Responsável</label>
                <select name="responsible_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('responsible_id', $project->responsible_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Departamento</label>
                <select name="department_id" class="input">
                    <option value="">— Nenhum —</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ old('department_id', $project->department_id) == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Status *</label>
                <select name="status" class="input" required>
                    <option value="ativo"     {{ old('status', $project->status) === 'ativo'     ? 'selected' : '' }}>Ativo</option>
                    <option value="concluido" {{ old('status', $project->status) === 'concluido' ? 'selected' : '' }}>Concluído</option>
                    <option value="pausado"   {{ old('status', $project->status) === 'pausado'   ? 'selected' : '' }}>Pausado</option>
                </select>
            </div>
            <div>
                <label class="label">Data de Início</label>
                <input type="date" name="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="input">
            </div>
            <div>
                <label class="label">Data de Encerramento</label>
                <input type="date" name="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}" class="input">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição</label>
                <textarea name="description" rows="4" class="input">{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Objetivos</label>
                <textarea name="objectives" rows="3" class="input">{{ old('objectives', $project->objectives) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Imagem do Projeto</label>
                @if($action === 'edit' && $project->image)
                    <img src="{{ Storage::url($project->image) }}" class="w-32 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="input">
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $project->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <span class="text-sm text-gray-700">Projeto visível no site</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.projects.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
