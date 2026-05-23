@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Curso' : 'Editar: ' . $course->title)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.courses.store') : route('admin.courses.update', $course) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Título do Curso *</label>
                <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="input" placeholder="Ex: Técnico em Agropecuária">
            </div>
            <div>
                <label class="label">Modalidade *</label>
                <input type="text" name="type" value="{{ old('type', $course->type) }}" required class="input" placeholder="Ex: Integrado ao Médio, Modular">
            </div>
            <div>
                <label class="label">Unidade</label>
                <select name="unit_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" {{ old('unit_id', $course->unit_id) == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Coordenador Técnico</label>
                <select name="technical_coordinator_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('technical_coordinator_id', $course->technical_coordinator_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Coordenador Descentralizado</label>
                <select name="decentralized_coordinator_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('decentralized_coordinator_id', $course->decentralized_coordinator_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="label">Descrição resumida *</label>
                <textarea name="description" rows="3" required class="input">{{ old('description', $course->description) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Conteúdo completo (grade curricular, perfil)</label>
                <textarea name="content" rows="8" class="input">{{ old('content', $course->content) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Horários</label>
                <textarea name="schedule" rows="3" class="input">{{ old('schedule', $course->schedule) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">Foto do Curso</label>
                @if($action === 'edit' && $course->image)
                    <img src="{{ Storage::url($course->image) }}" class="w-32 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="input">
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $course->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <span class="text-sm text-gray-700">Curso ativo (visível no site)</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.courses.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
