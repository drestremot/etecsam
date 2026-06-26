@extends('admin.layouts.app')
@section('page-title', 'Valores das Mensalidades - Moradia Estudantil')

@section('header-actions')
    <a href="{{ route('admin.cooperative-housing-fees.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-2 rounded-lg">+ Novo Mês</a>
@endsection

@section('content')
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mês</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Valor</th>
                <th class="px-4 py-3 w-28"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($cooperativeHousingFees as $fee)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-800">{{ $fee->month->translatedFormat('F \d\e Y') }}</td>
                <td class="px-4 py-3 text-gray-600">R$ {{ number_format($fee->amount, 2, ',', '.') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-1">
                        <a href="{{ route('admin.cooperative-housing-fees.edit', $fee) }}" title="Editar"
                           class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.cooperative-housing-fees.destroy', $fee) }}" method="POST" class="inline"
                              onsubmit="return confirm('Remover este mês? Os pagamentos registrados para ele tambem serao removidos.')">
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
            <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">Nenhum mês cadastrado ainda.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3">{{ $cooperativeHousingFees->links() }}</div>
</div>
@endsection
