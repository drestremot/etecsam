@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Funcionário' : 'Editar: ' . $teacher->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.teachers.store') : route('admin.teachers.update', $teacher) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Nome completo *</label>
                <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required class="input">
            </div>
            <div>
                <label class="label">Cargo / Função *</label>
                <input type="text" name="role" value="{{ old('role', $teacher->role) }}" required class="input" placeholder="Ex: Diretor, Professor, Agente Adm.">
            </div>
            <div>
                <label class="label">Especialidade</label>
                <input type="text" name="specialty" value="{{ old('specialty', $teacher->specialty) }}" class="input" placeholder="Ex: Agronomia, TI">
            </div>
            <div>
                <label class="label">E-mail</label>
                <input type="email" name="email" value="{{ old('email', $teacher->email) }}" class="input">
            </div>
            <div>
                <label class="label">Telefone</label>
                <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" class="input" placeholder="(18) 9xxxx-xxxx">
            </div>
            <div class="col-span-2">
                <label class="label">Currículo Lattes (URL)</label>
                <input type="url" name="lattes_url" value="{{ old('lattes_url', $teacher->lattes_url) }}" class="input" placeholder="http://lattes.cnpq.br/...">
            </div>
            <div class="col-span-2">
                <label class="label">Foto</label>
                @if($action === 'edit' && $teacher->photo)
                    <img src="{{ Storage::url($teacher->photo) }}" class="w-20 h-20 rounded-full object-cover mb-2">
                @endif
                <input type="file" name="photo" accept="image/*" class="input">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.teachers.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
