@extends('admin.layouts.app')
@section('page-title', 'Documentos da APM')

@section('header-actions')
    <a href="{{ route('admin.apm-reports.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden" x-data="adminTable()">
    <div class="px-4 py-3 border-b border-gray-100 flex flex-wrap items-center justify-between gap-3 bg-gray-50/50">
        <div class="flex items-center gap-2 flex-1 min-w-[200px]">
            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input x-model="q" @input="search()" type="text" placeholder="Buscar por título, categoria, período..."
                   class="flex-1 text-sm border-0 outline-none bg-transparent text-gray-700 placeholder-gray-400">
            <button x-show="q" @click="q='';search()" class="text-gray-400 hover:text-gray-600 text-xs">limpar</button>
        </div>
        @include('admin.partials.per-page-selector')
    </div>

    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th @click="sort('titulo')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Título <span class="ml-1 text-gray-400" x-text="icon('titulo')"></span>
                </th>
                <th @click="sort('cat')" class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase cursor-pointer hover:bg-gray-100 select-none">
                    Categoria <span class="ml-1 text-gray-400" x-text="icon('cat')"></span>
                </th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Período</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Publicado em</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Arquivo</th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($apmReports as $report)
            <tr class="hover:bg-gray-50"
                data-row="{{ strtolower($report->title . ' ' . $report->category . ' ' . ($report->period ?? '')) }}"
                data-active="1"
                data-titulo="{{ strtolower($report->title) }}"
                data-cat="{{ strtolower($report->category) }}">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $report->title }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-700">{{ $report->category }}</span>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $report->period ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $report->published_at ? $report->published_at->format('d/m/Y') : '—' }}</td>
                <td class="px-4 py-3 text-gray-500">
                    @if($report->file_path)
                        <a href="{{ photo_url($report->file_path) }}" target="_blank" class="text-indigo-600 hover:underline"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Abrir</a>
                    @elseif($report->url)
                        <a href="{{ $report->url }}" target="_blank" class="text-indigo-600 hover:underline"><svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg> Link</a>
                    @else
                        —
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.apm-reports.edit', $report) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.apm-reports.destroy', $report) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover {{ addslashes($report->title) }}?')">
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
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    @include('admin.partials.pagination-footer')
</div>
@endsection
