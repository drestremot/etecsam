@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Novo Valor de Mensalidade' : 'Editar Valor de Mensalidade')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.cooperative-housing-fees.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.cooperative-housing-fees.store') : route('admin.cooperative-housing-fees.update', $cooperativeHousingFee) }}"
          method="POST" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Mês e Valor</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Mês de referência <span class="text-red-500">*</span>
                    </label>
                    <input type="month" name="month"
                           value="{{ old('month', $cooperativeHousingFee->month ? $cooperativeHousingFee->month->format('Y-m') : '') }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    @error('month') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Valor da Mensalidade (R$) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="amount" step="0.01" min="0"
                           value="{{ old('amount', $cooperativeHousingFee->amount) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: 350.00">
                    @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.cooperative-housing-fees.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
