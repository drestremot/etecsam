@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 py-4 sm:py-6 pb-20 sm:pb-6">
    <div class="w-full max-w-2xl mx-auto space-y-5">
        
        <div class="flex items-center gap-3">
            <a href="{{ route('lab.materials.index') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white border border-gray-200 text-gray-700 shadow-2xs hover:bg-gray-50 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-800 uppercase">Editar: {{ $material->name }}</h1>
                <p class="text-xs text-gray-500">Atualize as informações do item do inventário</p>
            </div>
        </div>

        <form action="{{ route('lab.materials.update', $material) }}" method="POST" enctype="multipart/form-data" class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nome do Material / Equipamento *</label>
                <input type="text" name="name" value="{{ old('name', $material->name) }}" required
                       class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                @error('name')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Descrição</label>
                <textarea name="description" rows="2"
                          class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 resize-none">{{ old('description', $material->description) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Qtd. em Estoque *</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $material->stock_quantity) }}" min="0" required
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('stock_quantity')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Unidade de Medida</label>
                    <input type="text" name="unit" value="{{ old('unit', $material->unit) }}" placeholder="Ex: unid, kit, caixa, par"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Nº de Patrimônio</label>
                    <input type="text" name="patrimony_number" value="{{ old('patrimony_number', $material->patrimony_number) }}" placeholder="Ex: PAT-2026-004"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-purple-500">
                    @error('patrimony_number')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Foto do Material</label>
                @if($material->photo)
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ Storage::url($material->photo) }}" class="w-16 h-16 rounded-xl object-cover border border-gray-200">
                        <span class="text-xs text-gray-500">Foto atual. Envie outra para substituir.</span>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*"
                       class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2 text-xs text-gray-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-100 file:text-purple-700">
                @error('photo')<p class="text-red-500 text-[11px] mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                <a href="{{ route('lab.materials.index') }}" class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-xs font-bold text-gray-600 hover:bg-gray-100 transition">Cancelar</a>
                <button type="submit" class="rounded-xl bg-purple-600 px-5 py-2.5 text-xs font-bold text-white shadow-sm hover:bg-purple-500 transition">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>
@endsection
