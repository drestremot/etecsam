@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Departamento' : 'Editar: ' . $department->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.departments.store') : route('admin.departments.update', $department) }}"
          method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Nome do Departamento *</label>
                <input type="text" name="name" value="{{ old('name', $department->name) }}" required class="input" placeholder="Ex: Departamento de Informática">
            </div>
            <div class="col-span-2">
                <label class="label">Responsável</label>
                <select name="responsible_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('responsible_id', $department->responsible_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }} — {{ $t->role }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">E-mail do Departamento</label>
                <input type="email" name="email" value="{{ old('email', $department->email) }}" class="input">
            </div>
            <div>
                <label class="label">Telefone / Ramal</label>
                <input type="text" name="phone" value="{{ old('phone', $department->phone) }}" class="input" placeholder="(18) 3702-xxxx">
            </div>
            <div class="col-span-2">
                <label class="label">Localização (sala, bloco)</label>
                <input type="text" name="location" value="{{ old('location', $department->location) }}" class="input" placeholder="Ex: Bloco A, Sala 12">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição</label>
                <textarea name="description" rows="4" class="input">{{ old('description', $department->description) }}</textarea>
            </div>
            <div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600">
                    <span class="text-sm text-gray-700">Departamento ativo</span>
                </label>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.departments.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
