@extends('admin.layouts.app')
@section('page-title', 'Unidades Escolares')

@section('header-actions')
    <a href="{{ route('admin.units.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Nova</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Unidade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cidade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Coordenador</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($units as $unit)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $unit->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $unit->city }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $unit->coordinator?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.units.edit', $unit) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.units.destroy', $unit) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-400">Nenhuma unidade cadastrada.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $units->links() }}</div>
</div>
@endsection
