@extends('admin.layouts.app')
@section('title', 'Editar Espaço')
@section('content')
<div class="max-w-xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('lab.spaces.index') }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-white transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Editar: {{ $space->name }}</h1>
    </div>
    <form action="{{ route('lab.spaces.update', $space) }}" method="POST" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 space-y-5">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nome *</label>
            <input type="text" name="name" value="{{ old('name', $space->name) }}" required
                   class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Descrição</label>
            <textarea name="description" rows="3"
                      class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none">{{ old('description', $space->description) }}</textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Laboratório vinculado</label>
            <select name="laboratory_id" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                <option value="">Nenhum</option>
                @foreach($laboratories as $lab)
                <option value="{{ $lab->id }}" {{ old('laboratory_id', $space->laboratory_id) == $lab->id ? 'selected' : '' }}>{{ $lab->name }}</option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Vincula este espaço a um laboratório do catálogo do site</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Auxiliar responsável</label>
            <select name="auxiliar_id" class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                <option value="">Nenhum</option>
                @foreach($auxiliares as $a)
                <option value="{{ $a->id }}" {{ old('auxiliar_id', $space->auxiliar_id) == $a->id ? 'selected' : '' }}>{{ $a->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex justify-end gap-3 pt-2">
            <a href="{{ route('lab.spaces.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-200 dark:border-gray-600 text-sm font-semibold text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">Cancelar</a>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-etec-dark text-white text-sm font-semibold hover:bg-etec-main transition">Salvar</button>
        </div>
    </form>
</div>
@endsection
