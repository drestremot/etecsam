@extends('admin.layouts.app')
@section('content')
<div class="p-6">
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Temas do Site</h1>
            <p class="text-sm text-gray-500 mt-1">Campanhas de saúde, datas comemorativas e eventos especiais</p>
        </div>
        <div class="flex gap-3">
            @if($active)
            <form method="POST" action="{{ route('admin.themes.deactivate') }}">
                @csrf
                <button type="submit"
                        class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Restaurar Padrão
                </button>
            </form>
            @endif
            <a href="{{ route('admin.themes.create') }}"
               class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Novo Tema
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif

    @if($active)
    <div class="mb-6 p-4 rounded-xl border-2 border-dashed flex items-center gap-4"
         style="border-color: {{ $active->accent_color }}; background: {{ $active->primary_color }}18;">
        <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center"
             style="background: linear-gradient(135deg, {{ $active->primary_color }}, {{ $active->accent_color }})">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-gray-800">Tema ativo: <span style="color: {{ $active->primary_color }}">{{ $active->name }}</span></p>
            <p class="text-xs text-gray-500">Os visitantes verão as cores deste tema e o popup de apresentação.</p>
        </div>
    </div>
    @else
    <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200 text-sm text-gray-500 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Nenhum tema ativo — o site exibe as cores padrão (azul institucional).
    </div>
    @endif

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($themes as $theme)
        <div class="bg-white rounded-xl border-2 {{ $theme->active ? 'border-indigo-500 shadow-lg' : 'border-gray-100' }} overflow-hidden hover:shadow-md transition">

            {{-- Cabeçalho colorido --}}
            <div class="h-20 relative flex items-end px-4 pb-3"
                 style="background: linear-gradient(135deg, {{ $theme->primary_color }}, {{ $theme->secondary_color }})">
                <div class="absolute top-3 right-3 flex gap-1.5">
                    <div class="w-5 h-5 rounded-full border-2 border-white/60 shadow-sm" style="background:{{ $theme->primary_color }}"></div>
                    <div class="w-5 h-5 rounded-full border-2 border-white/60 shadow-sm" style="background:{{ $theme->secondary_color }}"></div>
                    <div class="w-5 h-5 rounded-full border-2 border-white/60 shadow-sm" style="background:{{ $theme->accent_color }}"></div>
                </div>
                @if($theme->active)
                <span class="absolute top-3 left-3 bg-white/25 text-white text-xs px-2 py-0.5 rounded-full font-bold backdrop-blur-sm">ATIVO</span>
                @endif
                <span class="text-white font-bold text-sm drop-shadow leading-tight">{{ $theme->name }}</span>
            </div>

            <div class="p-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-2">
                    {{ $theme->month ? ($months[$theme->month] ?? 'Mês '.$theme->month) : 'Tema especial' }}
                </p>

                @if($theme->description)
                <p class="text-xs text-gray-600 leading-relaxed line-clamp-3 mb-3">{{ $theme->description }}</p>
                @else
                <p class="text-xs text-gray-300 italic mb-3">Sem descrição cadastrada</p>
                @endif

                <div class="flex gap-2 flex-wrap pt-2 border-t border-gray-100">
                    @if(!$theme->active)
                    <form method="POST" action="{{ route('admin.themes.activate', $theme) }}">
                        @csrf
                        <button type="submit"
                                class="text-white text-xs px-3 py-1.5 rounded-lg font-semibold transition hover:opacity-90 shadow-sm"
                                style="background: {{ $theme->primary_color }}">
                            Ativar
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('admin.themes.edit', $theme) }}"
                       class="bg-gray-50 text-gray-700 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-100 transition border border-gray-200">
                        Editar
                    </a>
                    @if(!$theme->active)
                    <form method="POST" action="{{ route('admin.themes.destroy', $theme) }}"
                          onsubmit="return confirm('Remover o tema {{ $theme->name }}?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-50 text-red-600 px-3 py-1.5 rounded-lg text-xs hover:bg-red-100 transition">Excluir</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 py-16 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
            <p class="font-medium">Nenhum tema cadastrado ainda.</p>
            <a href="{{ route('admin.themes.create') }}" class="text-indigo-600 text-sm hover:underline mt-1 inline-block">Criar primeiro tema</a>
        </div>
        @endforelse
    </div>
</div>
@endsection
