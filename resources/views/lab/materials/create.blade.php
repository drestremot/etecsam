@extends('admin.layouts.app')
@section('title', 'Novo Material')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.materials.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Novo Material</h1>
    </div>

    <form action="{{ route('lab.materials.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 space-y-5">
        @csrf

        <div class="grid sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nome *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       placeholder="Ex: Microscópio Binocular"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Descrição</label>
                <textarea name="description" rows="2"
                          class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none"
                          placeholder="Detalhes sobre o material...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Qtd. em estoque *</label>
                <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 1) }}" min="0" required
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('stock_quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Unidade</label>
                <input type="text" name="unit" value="{{ old('unit') }}" placeholder="Ex: unid., kit, par"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nº Patrimônio</label>
                <input type="text" name="patrimony_number" value="{{ old('patrimony_number') }}"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('patrimony_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Foto</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2 text-sm dark:bg-gray-700 dark:text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-etec-light file:text-etec-dark">
                @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('lab.materials.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-etec-dark text-white text-sm font-semibold hover:bg-etec-main transition">Salvar</button>
        </div>
    </form>
</div>
@endsection
