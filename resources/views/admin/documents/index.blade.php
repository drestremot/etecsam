@extends('admin.layouts.app')
@section('page-title', 'Documentos e Downloads')

@section('header-actions')
    <a href="{{ route('admin.documents.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Categoria</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Arquivo / Link</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($documents as $doc)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $doc->title }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">{{ $doc->category }}</span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    @if(!empty($doc->file_path))
                        <a href="{{ photo_url($doc->file_path) }}" target="_blank" class="text-indigo-600 hover:underline">Arquivo</a>
                    @elseif(!empty($doc->url))
                        <a href="{{ $doc->url }}" target="_blank" class="text-indigo-600 hover:underline truncate block max-w-xs">Link externo</a>
                    @else
                        —
                    @endif
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.documents.edit', $doc) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.documents.destroy', $doc) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum documento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $documents->links() }}</div>
</div>
@endsection
