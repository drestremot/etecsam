@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Documento' : 'Editar Documento')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.documents.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.documents.store') : route('admin.documents.update', $document) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identificação</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title', $document->title) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Regimento Escolar 2025">
                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['Administrativo', 'Secretaria', 'Biblioteca', 'Acadêmico', 'Geral'] as $cat)
                        <label class="flex items-center gap-2 p-3 rounded-lg border-2 cursor-pointer transition text-sm
                            {{ old('category', $document->category) === $cat ? 'border-indigo-500 bg-indigo-50 text-indigo-700 font-semibold' : 'border-gray-200 hover:border-gray-300 text-gray-600' }}">
                            <input type="radio" name="category" value="{{ $cat }}"
                                   {{ old('category', $document->category) === $cat ? 'checked' : '' }}
                                   class="text-indigo-600 focus:ring-indigo-500">
                            {{ $cat }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição</label>
                    <textarea name="description" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Breve descrição do documento...">{{ old('description', $document->description) }}</textarea>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Arquivo</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Upload de arquivo</label>
                    @if($action === 'edit' && !empty($document->file_path))
                        <div class="flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="text-2xl">📄</span>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Arquivo atual</p>
                                <a href="{{ Storage::url($document->file_path) }}" target="_blank"
                                   class="text-xs text-indigo-600 hover:underline">Abrir arquivo</a>
                            </div>
                        </div>
                    @endif
                    <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    <p class="mt-2 text-xs text-gray-400">PDF, DOC, DOCX, XLS ou XLSX — máx. 10 MB</p>
                </div>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-xs">
                        <span class="px-3 bg-white text-gray-400 font-medium">ou use um link externo</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">URL externa</label>
                    <input type="url" name="url" value="{{ old('url', $document->url) }}"
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="https://drive.google.com/...">
                    <p class="mt-1.5 text-xs text-gray-400">Use se o arquivo estiver no Google Drive, SharePoint ou outro serviço.</p>
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Documento' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.documents.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
