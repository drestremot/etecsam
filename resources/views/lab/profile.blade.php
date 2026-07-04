@extends('admin.layouts.app')
@section('title', 'Meu Perfil')
@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Meu Perfil</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">{{ session('success') }}</div>
    @endif

    <form action="{{ route('lab.profile.update') }}" method="POST" enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 shadow-sm p-6 space-y-6">
        @csrf @method('PUT')

        {{-- Foto --}}
        <div class="flex items-center gap-6">
            <div class="relative">
                @if($teacher?->photo)
                    <img src="{{ photo_url($teacher->photo) }}" alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-4 border-etec-light">
                @else
                    <div class="w-24 h-24 rounded-full bg-etec-dark flex items-center justify-center text-white text-3xl font-bold border-4 border-etec-light">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Foto de perfil</label>
                <input type="file" name="photo" accept="image/*"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3 py-2 text-sm dark:bg-gray-700 dark:text-white file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-etec-light file:text-etec-dark">
                @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-400 mt-1">Aparece no site público na lista de professores</p>
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Nome completo *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">E-mail</label>
                <input type="email" value="{{ $user->email }}" disabled
                       class="w-full border border-gray-100 dark:border-gray-700 rounded-lg px-3.5 py-2.5 text-sm bg-gray-50 dark:bg-gray-700/50 text-gray-400 cursor-not-allowed">
                <p class="text-xs text-gray-400 mt-1">E-mail não pode ser alterado aqui</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Data de nascimento</label>
                <input type="date" name="birth_date"
                       value="{{ old('birth_date', $teacher?->birth_date ? \Carbon\Carbon::parse($teacher->birth_date)->format('Y-m-d') : '') }}"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Telefone</label>
                <input type="text" name="phone" value="{{ old('phone', $teacher?->phone) }}"
                       placeholder="(18) 99999-9999"
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Currículo Lattes</label>
                <input type="url" name="lattes_url" value="{{ old('lattes_url', $teacher?->lattes_url) }}"
                       placeholder="http://lattes.cnpq.br/..."
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('lattes_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Especialidade / Área de atuação</label>
                <input type="text" name="specialty" value="{{ old('specialty', $teacher?->specialty) }}"
                       placeholder="Ex: Zootecnia, Informática Aplicada..."
                       class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none">
                @error('specialty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Apresentação / Bio</label>
                <textarea name="bio" rows="4"
                          placeholder="Breve apresentação que aparece no site..."
                          class="w-full border border-gray-200 dark:border-gray-600 rounded-lg px-3.5 py-2.5 text-sm dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-etec-dark outline-none resize-none">{{ old('bio', $teacher?->bio) }}</textarea>
                @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                <p class="text-xs text-gray-400 mt-1">Máx. 1000 caracteres. Aparece no site público.</p>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-etec-dark text-white text-sm font-semibold hover:bg-etec-main transition">
                Salvar Perfil
            </button>
        </div>
    </form>
</div>
@endsection
