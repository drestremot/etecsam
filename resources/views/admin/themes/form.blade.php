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
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="{{ $theme->id ? route('admin.themes.update', $theme) : route('admin.themes.store') }}">
            @csrf @if($theme->id) @method('PUT') @endif
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nome *</label>
                    <input type="text" name="name" value="{{ old('name', $theme->name) }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                @if(!$theme->id)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Slug (identificador unico)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="setembro-amarelo">
                </div>
                @endif
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mes de referencia</label>
                    <select name="month" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tema geral (sem mes fixo)</option>
                        @foreach($months as $num => $name)
                        <option value="{{ $num }}" {{ old('month', $theme->month) == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cor Primaria</label>
                        <div class="flex gap-2">
                            <input type="color" id="pc" name="primary_color" value="{{ old('primary_color', $theme->primary_color ?? '#cc0000') }}" class="h-10 w-12 rounded border p-0.5 cursor-pointer">
                            <input type="text" value="{{ old('primary_color', $theme->primary_color ?? '#cc0000') }}" class="flex-1 border rounded-lg px-2 py-2 text-sm font-mono" oninput="document.getElementById('pc').value=this.value">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cor Secundaria</label>
                        <div class="flex gap-2">
                            <input type="color" id="sc" name="secondary_color" value="{{ old('secondary_color', $theme->secondary_color ?? '#ffffff') }}" class="h-10 w-12 rounded border p-0.5 cursor-pointer">
                            <input type="text" value="{{ old('secondary_color', $theme->secondary_color ?? '#ffffff') }}" class="flex-1 border rounded-lg px-2 py-2 text-sm font-mono" oninput="document.getElementById('sc').value=this.value">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cor de Destaque</label>
                        <div class="flex gap-2">
                            <input type="color" id="ac" name="accent_color" value="{{ old('accent_color', $theme->accent_color ?? '#ffcc00') }}" class="h-10 w-12 rounded border p-0.5 cursor-pointer">
                            <input type="text" value="{{ old('accent_color', $theme->accent_color ?? '#ffcc00') }}" class="flex-1 border rounded-lg px-2 py-2 text-sm font-mono" oninput="document.getElementById('ac').value=this.value">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Cor do Texto</label>
                        <div class="flex gap-2">
                            <input type="color" id="tc" name="text_color" value="{{ old('text_color', $theme->text_color ?? '#333333') }}" class="h-10 w-12 rounded border p-0.5 cursor-pointer">
                            <input type="text" value="{{ old('text_color', $theme->text_color ?? '#333333') }}" class="flex-1 border rounded-lg px-2 py-2 text-sm font-mono" oninput="document.getElementById('tc').value=this.value">
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex gap-3 mt-6 pt-4 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-indigo-700 transition">
                    {{ $theme->id ? 'Salvar' : 'Criar Tema' }}
                </button>
                <a href="{{ route('admin.themes.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-lg text-sm hover:bg-gray-200 transition">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
