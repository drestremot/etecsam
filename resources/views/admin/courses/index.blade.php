@extends('admin.layouts.app')
@section('page-title', 'Cursos Técnicos')

@section('header-actions')
    <a href="{{ route('admin.courses.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Curso</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Unidade</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Coord. Técnico</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($courses as $course)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $course->title }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">{{ $course->type }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $course->unit?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $course->technicalCoordinator?->name ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $course->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ $course->is_active ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.courses.subjects.index', $course) }}"
                           class="inline-flex items-center gap-1 text-xs font-medium text-emerald-600 hover:text-emerald-800 hover:underline"
                           title="Gerenciar Grade Curricular">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            Grade
                        </a>
                        <a href="{{ route('admin.courses.edit', $course) }}" class="text-indigo-600 hover:underline text-xs font-medium">Editar</a>
                        <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="inline" onsubmit="return confirm('Remover?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:underline text-xs font-medium">Excluir</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Nenhum curso cadastrado.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $courses->links() }}</div>
</div>
@endsection
