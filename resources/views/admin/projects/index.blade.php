@extends('admin.layouts.app')
@section('page-title', 'Projetos')

@section('header-actions')
    <a href="{{ route('admin.projects.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Projeto</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Responsável</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Departamento</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Período</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($projects as $project)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $project->name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $project->responsible?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $project->department?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    @php
                        $colors = ['ativo' => 'bg-green-100 text-green-700', 'concluido' => 'bg-blue-100 text-blue-700', 'pausado' => 'bg-yellow-100 text-yellow-700'];
                        $labels = ['ativo' => 'Ativo', 'concluido' => 'Concluído', 'pausado' => 'Pausado'];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $colors[$project->status] ?? '' }}">
                        {{ $labels[$project->status] ?? $project->status }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $project->start_date?->format('m/Y') ?? '—' }}
                    @if($project->end_date) → {{ $project->end_date->format('m/Y') }} @endif
                </td>
                <td class="px-4 py-3 text-right space-x-2">
                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-indigo-600 hover:underline">Editar</a>
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:underline">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum projeto cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $projects->links() }}</div>
</div>
@endsection
