@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Slide' : 'Editar Slide')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.home-slides.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.home-slides.store') : route('admin.home-slides.update', $homeSlide) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Conteúdo do Slide</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $homeSlide->title) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Etec Sebastiana Augusta de Moraes">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Texto curto exibido junto com a foto">{{ old('description', $homeSlide->description) }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Ordem de Exibição</label>
                        <input type="number" name="order" value="{{ old('order', $homeSlide->order ?? 0) }}" min="0"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <p class="mt-1 text-xs text-gray-400">Menor número aparece primeiro.</p>
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                   {{ old('is_active', $homeSlide->exists ? $homeSlide->is_active : true) ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-indigo-600">
                            <span class="text-sm font-medium text-gray-700">Exibir no site</span>
                        </label>
                    </div>
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Foto</h2>
            </div>
            <div class="p-6">
                @if($action === 'edit' && $homeSlide->image)
                    <div class="mb-4">
                        <img src="{{ photo_url($homeSlide->image) }}" alt="{{ $homeSlide->title }}"
                             class="w-full h-48 object-cover rounded-lg border border-gray-200">
                        <p class="text-xs text-gray-400 mt-1.5">Foto atual — selecione um novo arquivo para substituir.</p>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*" {{ $action === 'create' ? 'required' : '' }}
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                <p class="mt-2 text-xs text-gray-400">JPG, PNG ou WebP — máx. 6 MB. Recomendado: imagem larga (paisagem), mínimo 1600px de largura.</p>
                @error('image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Slide' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.home-slides.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
