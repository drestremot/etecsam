@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Nova Unidade' : 'Editar: ' . $unit->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.units.store') : route('admin.units.update', $unit) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Nome da Unidade *</label>
                <input type="text" name="name" value="{{ old('name', $unit->name) }}" required class="input" placeholder="Ex: E.E. Armel Miranda (Youssef)">
            </div>
            <div>
                <label class="label">Cidade *</label>
                <input type="text" name="city" value="{{ old('city', $unit->city) }}" required class="input" placeholder="Ex: Castilho">
            </div>
            <div>
                <label class="label">Coordenador</label>
                <select name="coordinator_id" class="input">
                    <option value="">— Selecione —</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->id }}" {{ old('coordinator_id', $unit->coordinator_id) == $t->id ? 'selected' : '' }}>
                            {{ $t->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="label">Endereço</label>
                <input type="text" name="address" value="{{ old('address', $unit->address) }}" class="input" placeholder="Ex: Estrada Vicinal Km 5">
            </div>
            <div class="col-span-2">
                <label class="label">Foto da Unidade</label>
                @if($action === 'edit' && $unit->image)
                    <img src="{{ Storage::url($unit->image) }}" class="w-32 h-24 object-cover rounded-lg mb-2">
                @endif
                <input type="file" name="image" accept="image/*" class="input">
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.units.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
