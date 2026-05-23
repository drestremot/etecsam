@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Documento' : 'Editar Documento')

@section('content')
<div class="max-w-2xl">
    <form action="{{ $action === 'create' ? route('admin.documents.store') : route('admin.documents.update', $document) }}"
          method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm p-6 space-y-5">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="grid grid-cols-2 gap-5">
            <div class="col-span-2">
                <label class="label">Título *</label>
                <input type="text" name="title" value="{{ old('title', $document->title) }}" required class="input" placeholder="Ex: Regimento Escolar 2025">
            </div>
            <div>
                <label class="label">Categoria *</label>
                <select name="category" class="input" required>
                    @foreach(['Administrativo', 'Secretaria', 'Biblioteca', 'Acadêmico', 'Geral'] as $cat)
                        <option value="{{ $cat }}" {{ old('category', $document->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-span-2">
                <label class="label">Arquivo (PDF, DOC, XLS — até 10MB)</label>
                @if($action === 'edit' && !empty($document->file_path))
                    <p class="text-xs text-gray-500 mb-1">Arquivo atual: <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-indigo-600">abrir</a></p>
                @endif
                <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" class="input">
            </div>
            <div class="col-span-2">
                <label class="label">Ou URL externa</label>
                <input type="url" name="url" value="{{ old('url', $document->url) }}" class="input" placeholder="https://...">
                <p class="text-xs text-gray-400 mt-1">Use um link externo se o arquivo estiver hospedado em outro serviço.</p>
            </div>
            <div class="col-span-2">
                <label class="label">Descrição</label>
                <textarea name="description" rows="3" class="input">{{ old('description', $document->description) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg">
                {{ $action === 'create' ? 'Cadastrar' : 'Salvar alterações' }}
            </button>
            <a href="{{ route('admin.documents.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Cancelar</a>
        </div>
    </form>
</div>

<style>
    .label { @apply block text-sm font-medium text-gray-700 mb-1; }
    .input  { @apply w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-300 focus:border-indigo-500 outline-none; }
</style>
@endsection
