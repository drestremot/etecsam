@extends('admin.layouts.app')
@section('page-title', 'Professores e Funcionários')

@section('header-actions')
    <a href="{{ route('admin.teachers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nome</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Cargo / Função</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">E-mail</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Telefone</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($teachers as $teacher)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">
                    @if($teacher->photo)
                        <img src="{{ photo_url($teacher->photo) }}" class="w-8 h-8 rounded-full object-cover inline-block mr-2">
                    @endif
                    {{ $teacher->name }}
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $teacher->role }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $teacher->email ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $teacher->phone ?? '—' }}</td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.teachers.edit', $teacher) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST" class="inline" onsubmit="return confirm('Remover {{ $teacher->name }}?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-400">Nenhum registro encontrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $teachers->links() }}</div>
</div>
@endsection
