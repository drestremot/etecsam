@extends('admin.layouts.app')
@section('content')
<div class="p-6 max-w-2xl">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.themes.index') }}" class="text-gray-400 hover:text-gray-700 text-xl">&#8592;</a>
        <h1 class="text-2xl font-bold text-gray-800">{{ $title }}</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
    </div>
    @endif

    <form method="POST"
          action="{{ $theme->id ? route('admin.themes.update', $theme) : route('admin.themes.store') }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @if($theme->id) @method('PUT') @endif

        {{-- Identidade --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identidade do Tema</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $theme->name) }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Ex: Outubro Rosa, Natal, Copa do Mundo">
                </div>

                @if(!$theme->id)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug <span class="text-red-500">*</span></label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-500"
                           placeholder="outubro-rosa">
                    <p class="text-xs text-gray-400 mt-1">Identificador único, sem espaços ou acentos</p>
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mês de referência</label>
                    <select name="month" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tema especial (sem mês fixo)</option>
                        @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ old('month', $theme->month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Descrição da Campanha</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 resize-none"
                              placeholder="Descreva o significado das cores e a causa que o tema representa. Este texto aparecerá no popup para os visitantes do site.">{{ old('description', $theme->description) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">Aparece no popup de boas-vindas quando o tema está ativo</p>
                </div>
            </div>
        </div>

        {{-- Cores --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Paleta de Cores</h2>
                <div id="preview-bar" class="h-6 w-48 rounded-full shadow-inner"
                     style="background: linear-gradient(90deg,
                        {{ old('primary_color',   $theme->primary_color   ?? '#1a3a6e') }},
                        {{ old('secondary_color', $theme->secondary_color ?? '#2563eb') }},
                        {{ old('accent_color',    $theme->accent_color    ?? '#f5a623') }})">
                </div>
            </div>
            <div class="p-6 grid grid-cols-2 gap-5">
                @foreach([
                    ['primary_color',   'Cor Principal (cabeçalho, hero)',   '#1a3a6e', 'pc'],
                    ['secondary_color', 'Cor Secundária (nav, botões)',       '#2563eb', 'sc'],
                    ['accent_color',    'Cor de Destaque (bordas, badges)',   '#f5a623', 'ac'],
                    ['text_color',      'Texto sobre fundo colorido',         '#ffffff', 'tc'],
                ] as [$field, $label, $default, $id])
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">{{ $label }}</label>
                    <div class="flex gap-2 items-center">
                        <input type="color" id="{{ $id }}" name="{{ $field }}"
                               value="{{ old($field, $theme->$field ?? $default) }}"
                               class="h-10 w-12 rounded border p-0.5 cursor-pointer flex-shrink-0"
                               oninput="syncColor('{{ $id }}', this.value)">
                        <input type="text" id="{{ $id }}_text"
                               value="{{ old($field, $theme->$field ?? $default) }}"
                               class="flex-1 border rounded-lg px-2 py-2 text-sm font-mono"
                               oninput="document.getElementById('{{ $id }}').value=this.value; updatePreview()">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Imagem do Popup --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Imagem do Popup</h2>
            </div>
            <div class="p-6">
                @if($theme->popup_image)
                <div class="flex items-center gap-4 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <img src="{{ photo_url($theme->popup_image) }}" class="w-24 h-16 object-cover rounded-lg border">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Imagem atual do popup</p>
                        <p class="text-xs text-gray-400 mt-0.5">Envie uma nova para substituir</p>
                    </div>
                </div>
                @endif
                <input type="file" name="popup_image" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                <p class="mt-2 text-xs text-gray-400">JPG, PNG ou WebP — máx. 2 MB. Sugestão: laço, fita ou símbolo da campanha (800×400px)</p>
            </div>
        </div>

        <div class="flex gap-3 pt-1">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                {{ $theme->id ? '✓ Salvar Tema' : '✓ Criar Tema' }}
            </button>
            <a href="{{ route('admin.themes.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">Cancelar</a>
        </div>
    </form>
</div>

<script>
function syncColor(id, value) {
    document.getElementById(id + '_text').value = value;
    updatePreview();
}
function updatePreview() {
    const p = document.getElementById('pc').value;
    const s = document.getElementById('sc').value;
    const a = document.getElementById('ac').value;
    document.getElementById('preview-bar').style.background =
        `linear-gradient(90deg, ${p}, ${s}, ${a})`;
}
</script>
@endsection
