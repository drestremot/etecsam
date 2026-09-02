@extends('layouts.operational')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
@endpush

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-4xl mx-auto space-y-6">

        <div class="flex items-center justify-between">
            <div>
                <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-gray-600 hover:text-indigo-600 transition mb-2">
                    &larr; Voltar para a lista
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900">
                    {{ $action === 'create' ? 'Novo Colaborador' : 'Editar: ' . $teacher->name }}
                </h1>
            </div>
        </div>

        <form action="{{ $action === 'create' ? route('admin.teachers.store') : route('admin.teachers.update', $teacher) }}"
              method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @if($action === 'edit') @method('PUT') @endif

            {{-- Dados Pessoais --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70">
                    <h2 class="text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wide">Dados Pessoais & Identificação</h2>
                </div>
                <div class="p-6 space-y-5">

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                            Nome completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                               class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Nome completo do funcionário">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">
                                Cargo / Função <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="role" value="{{ old('role', $teacher->role) }}" required
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="Ex: Diretor, Professor, Auxiliar">
                            @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Especialidade / Disciplina</label>
                            <input type="text" name="specialty" value="{{ old('specialty', $teacher->specialty) }}"
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="Ex: Agronomia, TI, Gestão">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">E-mail Institucional</label>
                            <input type="email" name="email" value="{{ old('email', $teacher->email) }}"
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="email@etecsam.sp.gov.br">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Telefone / Ramal</label>
                            <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="(19) 99999-9999">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Link do Currículo Lattes</label>
                            <input type="url" name="lattes_url" value="{{ old('lattes_url', $teacher->lattes_url) }}"
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 placeholder-gray-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                                   placeholder="http://lattes.cnpq.br/...">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Data de Nascimento (opcional)</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $teacher->birth_date) }}"
                                   class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Foto de Perfil</label>
                        @if($teacher->photo)
                            <div class="flex items-center gap-4 mb-3">
                                <img src="{{ photo_url($teacher->photo) }}" alt="{{ $teacher->name }}"
                                     class="w-16 h-16 rounded-2xl object-cover border border-gray-200 shadow-2xs">
                                <p class="text-xs text-gray-500">Substitua enviando uma nova imagem abaixo.</p>
                            </div>
                        @endif
                        <input type="file" name="photo" accept="image/*"
                               class="w-full text-xs sm:text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    </div>

                </div>
            </div>

            {{-- Biografia e Apresentação --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/70">
                    <h2 class="text-xs sm:text-sm font-bold text-gray-700 uppercase tracking-wide">Biografia / Apresentação no Site</h2>
                </div>
                <div class="p-6">
                    <div id="quill-editor" class="min-h-[160px] bg-white rounded-xl border border-gray-200">
                        {!! old('bio', $teacher->bio) !!}
                    </div>
                    <textarea name="bio" id="bio-input" class="hidden">{{ old('bio', $teacher->bio) }}</textarea>
                </div>
            </div>

            {{-- Botões de Ação --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.teachers.index') }}" class="rounded-xl px-5 py-2.5 text-xs sm:text-sm font-bold text-gray-600 hover:bg-gray-200 transition">
                    Cancelar
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs sm:text-sm font-bold text-white shadow-sm hover:bg-indigo-500 transition">
                    {{ $action === 'create' ? 'Salvar Colaborador' : 'Atualizar Dados' }}
                </button>
            </div>
        </form>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Escreva um breve resumo da trajetória profissional...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                ['link', 'clean']
            ]
        }
    });

    const bioInput = document.getElementById('bio-input');
    quill.on('text-change', function () {
        bioInput.value = quill.root.innerHTML;
    });

    document.querySelector('form').addEventListener('submit', function () {
        bioInput.value = quill.root.innerHTML;
    });
});
</script>
@endpush
@endsection
