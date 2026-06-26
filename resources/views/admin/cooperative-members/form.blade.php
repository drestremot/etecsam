@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Cooperado' : 'Editar: ' . $cooperativeMember->name)

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.cooperative-members.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.cooperative-members.store') : route('admin.cooperative-members.update', $cooperativeMember) }}"
          method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados do Cooperado</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Nome completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name', $cooperativeMember->name) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Nome completo do cooperado">
                    @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Número de Matrícula / Registro</label>
                        <input type="text" name="registration_number" value="{{ old('registration_number', $cooperativeMember->registration_number) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: 0042">
                        @error('registration_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Sexo</label>
                        <select name="sex"
                                class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="">— Selecione —</option>
                            <option value="M" {{ old('sex', $cooperativeMember->sex) === 'M' ? 'selected' : '' }}>Masculino</option>
                            <option value="F" {{ old('sex', $cooperativeMember->sex) === 'F' ? 'selected' : '' }}>Feminino</option>
                        </select>
                        @error('sex') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telefone</label>
                        <input type="text" name="phone" value="{{ old('phone', $cooperativeMember->phone) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="(18) 9xxxx-xxxx">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">E-mail</label>
                        <input type="email" name="email" value="{{ old('email', $cooperativeMember->email) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="email@exemplo.com">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nome do Responsável</label>
                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $cooperativeMember->guardian_name) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Nome do responsável">
                        @error('guardian_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Telefone do Responsável</label>
                        <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $cooperativeMember->guardian_phone) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="(18) 9xxxx-xxxx">
                        @error('guardian_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de Entrada</label>
                    <input type="date" name="joined_at"
                           value="{{ old('joined_at', $cooperativeMember->joined_at ? $cooperativeMember->joined_at->format('Y-m-d') : '') }}"
                           class="w-full max-w-xs rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('joined_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        {{-- Foto --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Foto</h2>
            </div>
            <div class="p-6">
                @if($action === 'edit' && $cooperativeMember->photo)
                    <div class="flex items-center gap-4 mb-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <img src="{{ photo_url($cooperativeMember->photo) }}"
                             onerror="this.src='{{ avatar_url($cooperativeMember->name, '1a3a6e', 'fff', ['bold' => 'true', 'size' => 128]) }}'"
                             class="w-16 h-16 rounded-full object-cover border-2 border-white shadow">
                        <div>
                            <p class="text-sm font-medium text-gray-700">Foto atual</p>
                            <p class="text-xs text-gray-400 mt-0.5">Selecione um novo arquivo para substituir</p>
                        </div>
                    </div>
                @endif
                <input type="file" name="photo" accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                <p class="mt-2 text-xs text-gray-400">JPG, PNG ou WebP — máx. 4 MB</p>
                @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Cooperado' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.cooperative-members.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
