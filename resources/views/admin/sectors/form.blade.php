@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Setor' : 'Editar: ' . $sector->name)

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.sectors.store') : route('admin.sectors.update', $sector) }}"
          method="POST" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="label">Nome do Setor *</label>
                <input type="text" name="name" value="{{ old('name', $sector->name) }}" required class="input" placeholder="Ex: Bovinocultura">
            </div>
            <div>
                <label class="label">Ícone *</label>
                <select name="icon" class="input" required>
                    @php
                        $icons = ['cow'=>'🐮 Vaca', 'pig'=>'🐷 Porco', 'chicken'=>'🐔 Galinha', 'fish'=>'🐟 Peixe', 'bee'=>'🐝 Abelha', 'leaf'=>'🌱 Planta', 'tree'=>'🌳 Árvore', 'factory'=>'🏭 Fábrica', 'computer'=>'💻 Computador', 'flask'=>'🧪 Laboratório', 'cheese'=>'🧀 Queijo', 'meat'=>'🥩 Carne'];
                    @endphp
                    @foreach($icons as $val => $label)
                        <option value="{{ $val }}" {{ old('icon', $sector->icon) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="label">Resumo *</label>
                <input type="text" name="summary" value="{{ old('summary', $sector->summary) }}" required class="input" placeholder="Frase curta sobre o setor">
            </div>
            <div class="col-span-2">
                <label class="label">Descrição completa</label>
                <textarea name="description" rows="6" class="input">{{ old('description', $sector->description) }}</textarea>
            </div>
            <div class="col-span-2">
                <label class="label">URLs das imagens (uma por linha)</label>
                <textarea name="images" rows="4" class="input font-mono text-xs" placeholder="https://...&#10;https://...">{{ old('images', $sector->images ? implode("\n", $sector->images) : '') }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Cole uma URL de imagem por linha para o carrossel de fotos.</p>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.sectors.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
