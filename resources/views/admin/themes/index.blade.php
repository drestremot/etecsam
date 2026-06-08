@extends('admin.layouts.app')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Temas do Site</h1>
            <p class="text-sm text-gray-500 mt-1">Setembro Amarelo, Novembro Azul, Natal e outras datas comemorativas</p>
        </div>
        <div class="flex gap-3">
            <form method="POST" action="{{ route('admin.themes.deactivate') }}">
                @csrf
                <button type="submit" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                    Usar Padrao
                </button>
            </form>
            <a href="{{ route('admin.themes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
                + Novo Tema
            </a>
        </div>
    </div>
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('success') }}</div>
    @endif
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($themes as $theme)
        <div class="bg-white rounded-xl border-2 {{ $theme->active ? 'border-indigo-500 shadow-lg' : 'border-gray-100' }} overflow-hidden">
            <div class="h-16 flex items-center justify-between px-4" style="background: linear-gradient(135deg, {{ $theme->primary_color }}, {{ $theme->accent_color }})">
                <div class="flex gap-2">
                    <div class="w-6 h-6 rounded-full border-2 border-white/40" style="background:{{ $theme->primary_color }}"></div>
                    <div class="w-6 h-6 rounded-full border-2 border-white/40" style="background:{{ $theme->secondary_color }}"></div>
                    <div class="w-6 h-6 rounded-full border-2 border-white/40" style="background:{{ $theme->accent_color }}"></div>
                    <span class="font-semibold text-white text-sm drop-shadow ml-1">{{ $theme->name }}</span>
                </div>
                @if($theme->active)
                <span class="bg-white/30 text-white text-xs px-2 py-0.5 rounded-full font-bold">ATIVO</span>
                @endif
            </div>
            <div class="p-4">
                <p class="text-xs text-gray-400 mb-3">
                    {{ $theme->month ? ($months[$theme->month] ?? 'Mes '.$theme->month) : 'Tema geral' }}
                </p>
                <div class="flex gap-2 flex-wrap">
                    @if(!$theme->active)
                    <form method="POST" action="{{ route('admin.themes.activate', $theme) }}">
                        @csrf
                        <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-indigo-700 transition">Ativar</button>
                    </form>
                    @endif
                    <a href="{{ route('admin.themes.edit', $theme) }}" class="bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-100 transition">Editar</a>
                    @if(!$theme->active)
                    <form method="POST" action="{{ route('admin.themes.destroy', $theme) }}" onsubmit="return confirm('Remover tema?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs hover:bg-red-100 transition">Excluir</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
