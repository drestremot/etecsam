@extends('admin.layouts.app')
@section('page-title', 'Laboratórios')

@section('header-actions')
    <a href="{{ route('admin.laboratories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Laboratório</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Unidade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Capacidade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($laboratories as $lab)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $lab->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $lab->responsible?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $lab->unit?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $lab->capacity ? $lab->capacity . ' alunos' : '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $lab->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $lab->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.laboratories.edit', $lab) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.laboratories.destroy', $lab) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum laboratório cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $laboratories->links() }}</div>
</div>
@endsection
