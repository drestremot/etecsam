@extends('admin.layouts.app')
@section('page-title', 'Carrossel da Página Inicial')

@section('header-actions')
    <a href="{{ route('admin.home-slides.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo Slide</a>
@endsection

@section('content')
<p class="text-sm text-gray-500 mb-4">
    Fotos exibidas em rotação no topo da página inicial (Portal Institucional). A ordem abaixo segue o campo "Ordem" de cada slide.
</p>
<div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm rounded-lg px-4 py-3 mb-4">
    A foto institucional padrão ("Etec Sebastiana Augusta de Moraes") sempre aparece em primeiro no carrossel do site, antes destes slides — ela não pode ser removida ou desativada por aqui.
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Foto</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Descrição</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ordem</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 w-32"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($homeSlides as $slide)
            <tr class="hover:bg-gray-50 {{ !$slide->is_active ? 'opacity-60' : '' }}">
                <td class="px-4 py-3">
                    <img src="{{ photo_url($slide->image) }}" alt="{{ $slide->title }}" class="w-20 h-12 object-cover rounded-lg border border-gray-200">
                </td>
                <td class="px-4 py-3 font-medium text-gray-800">{{ $slide->title }}</td>
                <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $slide->description ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $slide->order }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $slide->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $slide->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <form action="{{ route('admin.home-slides.toggle', $slide) }}" method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" title="{{ $slide->is_active ? 'Desativar' : 'Ativar' }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg transition {{ $slide->is_active ? 'bg-green-50 text-green-600 hover:bg-green-100' : 'bg-gray-100 text-gray-400 hover:bg-gray-200' }}">
                                @if($slide->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </button>
                        </form>
                        <a href="{{ route('admin.home-slides.edit', $slide) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.home-slides.destroy', $slide) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover o slide {{ addslashes($slide->title) }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" title="Excluir"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum slide cadastrado ainda. O site mostrará a imagem padrão até que um slide seja criado.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
