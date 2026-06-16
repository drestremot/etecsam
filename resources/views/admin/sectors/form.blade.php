@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Setor / Unidade Didática' : 'Editar: ' . $sector->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.sectors.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.sectors.store') : route('admin.sectors.update', $sector) }}"
          method="POST" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identificação</h2>
            </div>
            <div class="p-6 space-y-5">

                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Nome do Setor <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $sector->name) }}" required
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: Bovinocultura">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Ícone <span class="text-red-500">*</span>
                        </label>
                        <select name="icon" class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white" required>
                            @php
                                $icons = ['cow'=>'🐮 Vaca','pig'=>'🐷 Porco','chicken'=>'🐔 Galinha','fish'=>'🐟 Peixe','bee'=>'🐝 Abelha','leaf'=>'🌱 Planta','tree'=>'🌳 Árvore','factory'=>'🏭 Fábrica','computer'=>'💻 Computador','flask'=>'🧪 Lab.','cheese'=>'🧀 Queijo','meat'=>'🥩 Carne'];
                            @endphp
                            @foreach($icons as $val => $label)
                                <option value="{{ $val }}" {{ old('icon', $sector->icon) === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Resumo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="summary" value="{{ old('summary', $sector->summary) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Frase curta exibida no card do setor">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição completa</label>
                    <textarea name="description" rows="6"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Descreva o setor, suas atividades, equipamentos e importância pedagógica...">{{ old('description', $sector->description) }}</textarea>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Galeria de Imagens</h2>
            </div>
            <div class="p-6">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">URLs das imagens</label>
                <textarea name="images" rows="5"
                          class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 font-mono placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                          placeholder="https://...&#10;https://...">{{ old('images', $sector->images ? implode("\n", $sector->images) : '') }}</textarea>
                <p class="mt-2 text-xs text-gray-400">Cole uma URL de imagem por linha para o carrossel de fotos.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $sector->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span>
                    <span class="text-sm font-semibold text-gray-700">Setor ativo</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Visível nas páginas do site</span>
                </span>
            </label>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Setor' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.sectors.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
