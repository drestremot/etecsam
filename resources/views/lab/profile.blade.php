@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-3xl mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 mb-1">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-extrabold">Minha Conta</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg> Meu Perfil</span>
                </h1>
                <p class="text-xs sm:text-sm text-gray-600 mt-1 font-normal">
                    Atualize seus dados de cadastro, biografia acadêmica e foto de exibição
                </p>
            </div>

            <div>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500 text-white p-4 text-xs sm:text-sm font-bold shadow-md flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        <form action="{{ route('lab.profile.update') }}" method="POST" enctype="multipart/form-data"
              class="rounded-2xl border border-gray-200 bg-white p-6 sm:p-8 shadow-xs space-y-6">
            @csrf @method('PUT')

            {{-- Foto de Perfil --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-6 border-b border-gray-100">
                <div class="relative flex-shrink-0">
                    @if($teacher?->photo)
                        <img src="{{ photo_url($teacher->photo) }}" alt="{{ $user->name }}"
                             class="w-24 h-24 rounded-2xl object-cover border-2 border-indigo-100 shadow-xs">
                    @else
                        <div class="w-24 h-24 rounded-2xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-3xl font-bold border-2 border-indigo-200 shadow-xs">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1 text-center sm:text-left">
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Foto de Perfil & Exibição</label>
                    <input type="file" name="photo" accept="image/*"
                           class="w-full text-xs text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition cursor-pointer">
                    @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1.5">Exibida no portal institucional e no cabeçalho do sistema.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Nome Completo *</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">E-mail Institucional</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full rounded-xl border border-gray-200 bg-gray-100 px-3.5 py-2.5 text-xs sm:text-sm text-gray-500 cursor-not-allowed">
                    <p class="text-[11px] text-gray-400 mt-1">O e-mail é a sua chave de acesso e não pode ser alterado aqui.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Data de Nascimento</label>
                    <input type="date" name="birth_date"
                           value="{{ old('birth_date', $teacher?->birth_date ? \Carbon\Carbon::parse($teacher->birth_date)->format('Y-m-d') : '') }}"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    @error('birth_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Telefone / WhatsApp</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher?->phone) }}"
                           placeholder="(19) 99999-9999"
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Currículo Lattes (URL)</label>
                    <input type="url" name="lattes_url" value="{{ old('lattes_url', $teacher?->lattes_url) }}"
                           placeholder="http://lattes.cnpq.br/..."
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    @error('lattes_url')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Especialidade / Área de Atuação</label>
                    <input type="text" name="specialty" value="{{ old('specialty', $teacher?->specialty) }}"
                           placeholder="Ex: Engenharia de Software, Zootecnia, Administração..."
                           class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition">
                    @error('specialty')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1.5">Apresentação / Biografia</label>
                    <textarea name="bio" rows="4"
                              placeholder="Breve apresentação acadêmica e profissional..."
                              class="w-full rounded-xl border border-gray-300 bg-gray-50/50 px-3.5 py-2.5 text-xs sm:text-sm text-gray-800 shadow-2xs focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-400 transition resize-none">{{ old('bio', $teacher?->bio) }}</textarea>
                    @error('bio')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p class="text-xs text-gray-400 mt-1">Máx. 1000 caracteres. Aparece no portal público da escola.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <a href="{{ route('dashboard') }}" class="rounded-xl border border-gray-300 bg-gray-50 px-4 py-2.5 text-xs font-semibold text-gray-600 hover:bg-gray-100 transition">
                    Cancelar
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-6 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition">
                    Salvar Alterações
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
