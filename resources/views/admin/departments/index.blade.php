@extends('admin.layouts.app')
@section('page-title', 'Departamentos')

@section('header-actions')
    <a href="{{ route('admin.departments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Departamento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Contato</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($departments as $dept)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $dept->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $dept->responsible?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $dept->email ?? '' }}
                    @if($dept->phone) <br>{{ $dept->phone }} @endif
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $dept->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $dept->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.departments.edit', $dept) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.departments.destroy', $dept) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum departamento cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $departments->links() }}</div>
</div>
@endsection
