@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Laboratório' : 'Editar: ' . $laboratory->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.laboratories.store') : route('admin.laboratories.update', $laboratory) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Nome do Laboratório *</label>
                <input type="text" name="name" value="{{ old('name', $laboratory->name) }}" required class="input" placeholder="Ex: Lab. de Informática 01">
            </div>
            <div class="col-span-2">
                <label class="label">Responsável</label>
                <select name="responsible_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('responsible_id', $laboratory->responsible_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} — {{ $t->role }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Unidade Escolar</label>
                <select name="unit_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ old('unit_id', $laboratory->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Curso vinculado</label>
                <select name="course_id" class="input">
                    <option value="">— Nenhum —</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ old('course_id', $laboratory->course_id) == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Localização (sala/bloco)</label>
                <input type="text" name="location" value="{{ old('location', $laboratory->location) }}" class="input" placeholder="Ex: Bloco B, térreo">
            </div>
            <div>
                <label class="label">Capacidade (alunos)</label>
                <input type="number" name="capacity" value="{{ old('capacity', $laboratory->capacity) }}" class="input" min="1">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição resumida</label>
                <input type="text" name="description" value="{{ old('description', $laboratory->description) }}" class="input">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição completa</label>
                <textarea name="full_description" rows="5" class="input">{{ old('full_description', $laboratory->full_description) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Foto</label>
                @if($action === 'edit' && $laboratory->photo)
                    <img src="{{ Storage::url($laboratory->photo) }}" class="w-32 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="photo" accept="image/*" class="input">
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $laboratory->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <span class="text-sm text-gray-700">Laboratório ativo</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.laboratories.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
