@extends('admin.layouts.app')
@section('page-title', $action === 'create' ? 'Nova Despesa' : 'Editar Despesa')

@section('content')
<div class="max-w-2xl">

    <div class="mb-4">
        <a href="{{ route('admin.cooperative-expenses.index') }}" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 transition">
            ← Voltar para lista
        </a>
    </div>

    <form action="{{ $action === 'create' ? route('admin.cooperative-expenses.store') : route('admin.cooperative-expenses.update', $cooperativeExpense) }}"
          method="POST" class="space-y-6">
        @csrf
        @if($action === 'edit') @method('PUT') @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Dados da Despesa</h2>
            </div>
            <div class="p-6 space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Descrição <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="description" value="{{ old('description', $cooperativeExpense->description) }}" required
                           class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                           placeholder="Ex: Compra de adubo, Manutenção do trator">
                    @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Categoria</label>
                        <input type="text" name="category" value="{{ old('category', $cooperativeExpense->category) }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: Insumos, Manutenção, Salários">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Valor (R$) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="amount" step="0.01" min="0" value="{{ old('amount', $cooperativeExpense->amount) }}" required
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                               placeholder="Ex: 150.00">
                        @error('amount') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Data de Vencimento <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date"
                               value="{{ old('due_date', $cooperativeExpense->due_date ? $cooperativeExpense->due_date->format('Y-m-d') : '') }}" required
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <p class="mt-1 text-xs text-gray-400">Use uma data futura para lançar uma previsão.</p>
                        @error('due_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Data de Pagamento</label>
                        <input type="date" name="paid_date"
                               value="{{ old('paid_date', $cooperativeExpense->paid_date ? $cooperativeExpense->paid_date->format('Y-m-d') : '') }}"
                               class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        <p class="mt-1 text-xs text-gray-400">Deixe vazio se ainda não foi pago.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Observações</label>
                    <textarea name="notes" rows="3"
                              class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none">{{ old('notes', $cooperativeExpense->notes) }}</textarea>
                </div>

            </div>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button type="submit"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg shadow-sm transition">
                {{ $action === 'create' ? '✓ Cadastrar Despesa' : '✓ Salvar Alterações' }}
            </button>
            <a href="{{ route('admin.cooperative-expenses.index') }}"
               class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 px-4 py-2.5 rounded-lg border border-gray-300 hover:bg-gray-50 transition">
                Cancelar
            </a>
        </div>

    </form>
</div>
@endsection
