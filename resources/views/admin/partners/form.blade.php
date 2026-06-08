@extends('admin.layouts.app')
@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.partners.index') }}" class="text-gray-400 hover:text-gray-700 text-xl">&#8592;</a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
    </div>
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $partner->id ? route('admin.partners.update', $partner) : route('admin.partners.store') }}" enctype="multipart/form-data">
            @csrf
            @if($partner->id) @method('PUT') @endif
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome *</label>
                    <input type="text" name="name" value="{{ old('name', $partner->name) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Nome do parceiro">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Site (URL)</label>
                    <input type="url" name="website" value="{{ old('website', $partner->website) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="https://">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Logotipo</label>
                    @if($partner->logo)
                    <div class="mb-2 flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <img src="{{ Storage::url($partner->logo) }}" class="h-12 rounded border border-gray-200 object-contain" alt="Logo atual">
                        <span class="text-xs text-gray-400">Logo atual</span>
                    </div>
                    @endif
                    <input type="file" name="logo" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG — Recomendado: fundo transparente</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descricao</label>
                    <textarea name="description" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">{{ old('description', $partner->description) }}</textarea>
                </div>
                <div class="flex items-center gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Ordem</label>
                        <input type="number" name="order" value="{{ old('order', $partner->order ?? 0) }}" min="0"
                            class="w-24 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex items-end pb-0.5">
                        <label class="flex items-center gap-2 cursor-pointer mt-5">
                            <input type="checkbox" name="active" value="1" {{ old('active', $partner->exists ? $partner->active : true) ? 'checked' : '' }} class="w-4 h-4 rounded accent-indigo-600">
                            <span class="text-sm font-medium text-gray-700">Exibir no site</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">Salvar</button>
                <a href="{{ route('admin.partners.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
