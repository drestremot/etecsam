@extends('admin.layouts.app')
@section('page-title', 'Setores / Escola Fazenda')

@section('header-actions')
    <a href="{{ route('admin.sectors.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Setor</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ícone</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Resumo</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($sectors as $sector)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $sector->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $sector->icon }}</td>
                <td class="px-4 py-3 text-gray-500 truncate max-w-xs">{{ Str::limit($sector->summary, 80) }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.sectors.edit', $sector) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.sectors.destroy', $sector) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhum setor cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $sectors->links() }}</div>
</div>
@endsection
