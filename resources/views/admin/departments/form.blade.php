@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Departamento' : 'Editar: ' . $department->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.departments.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.departments.store') : route('admin.departments.update', $department) }}"
          method="POST" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        {{-- Card principal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Identificação</h2>
            </div>

            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nome do Departamento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $department->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Departamento de Informática">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Responsável</label>
                    <select name="responsible_id"
                            class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition bg-white">
                        <option value="">— Selecione um responsável —</option>
                        @foreach($teachers as $t)
                            <option value="{{ $t->id }}" {{ old('responsible_id', $department->responsible_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->name }} — {{ $t->role }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">E-mail do Departamento</label>
                        <input type="email" name="email" value="{{ old('email', $department->email) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="depto@etec.sp.gov.br">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telefone / Ramal</label>
                        <input type="text" name="phone" value="{{ old('phone', $department->phone) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="(18) 3702-xxxx">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Localização</label>
                    <input type="text" name="location" value="{{ old('location', $department->location) }}"
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Bloco A, Sala 12">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Descrição</label>
                    <textarea name="description" rows="4"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"
                              placeholder="Descreva as funções e objetivos deste departamento...">{{ old('description', $department->description) }}</textarea>
                </div>

            </div>
        </div>

        {{-- Card de status --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <label class="flex items-center gap-3 cursor-pointer select-none">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $department->is_active ?? true) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span>
                    <span class="text-sm font-semibold text-gray-700">Departamento ativo</span>
                    <span class="block text-xs text-gray-400 mt-0.5">Visível nas páginas do site</span>
                </span>
            </label>
        </div>

        {{-- Botões --}}
        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Departamento' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.departments.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
