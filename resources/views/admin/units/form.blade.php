@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Nova Unidade Escolar' : 'Editar: ' . $unit->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.units.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.units.store') : route('admin.units.update', $unit) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados da Unidade</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nome da Unidade <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $unit->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: E.E. Armel Miranda (Youssef)">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Cidade <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="city" value="{{ old('city', $unit->city) }}" required
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: Castilho">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Coordenador</label>
                        <select name="coordinator_id"
                                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                            <option value="">— Selecione —</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('coordinator_id', $unit->coordinator_id) == $t->id ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Endereço</label>
                    <input type="text" name="address" value="{{ old('address', $unit->address) }}"
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Estrada Vicinal Km 5, zona rural">
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Foto da Unidade</h2>
            </div>
            <div class="p-6">
                @if($action === 'edit' && $unit->image)
                    <div class="flex items-center gap-4 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="{{ photo_url($unit->image) }}" class="w-20 h-14 rounded-lg object-cover border border-gray-200">
                        <p class="text-xs text-gray-400">Selecione um novo arquivo para substituir</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                <p class="mt-2 text-xs text-gray-400">JPG, PNG ou WebP — máx. 4 MB</p>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Unidade' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.units.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
