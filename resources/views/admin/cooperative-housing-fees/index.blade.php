@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8"
     x-data="{
         createOpen: false,
         editOpen: false,
         editFee: {
             id: null,
             month: '',
             amount: '',
             updateUrl: ''
         },
         openEdit(fee, updateUrl) {
             this.editFee = {
                 id: fee.id,
                 month: fee.month ? fee.month.substring(0, 7) : '',
                 amount: fee.amount || '',
                 updateUrl: updateUrl
             };
             this.editOpen = true;
         }
     }">

    <div class="w-full max-w-4xl mx-auto space-y-5">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-dashboard') }}" class="hover:text-amber-700 transition">Cooperativa Escola</a>
                    <span>/</span>
                    <a href="{{ route('admin.cooperative-housing-tenants.index') }}" class="hover:text-blue-700 transition">Moradia Estudantil</a>
                    <span>/</span>
                    <span class="text-blue-700 font-bold">Valores de Mensalidade</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </span>
                        Competências e Valores - Moradia Estudantil
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Definição das taxas cobradas mensalmente aos residentes do alojamento
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button @click="createOpen = !createOpen"
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-500 active:scale-95">
                    <svg class="h-3.5 w-3.5 transition-transform duration-200" :class="createOpen ? 'rotate-45' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span x-text="createOpen ? 'Fechar' : '+ Novo Mês / Valor'"></span>
                </button>

                <a href="{{ route('admin.cooperative-housing-tenants.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-700 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-600">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Voltar aos Moradores</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Hub</span>
                </a>
            </div>
        </div>

        {{-- Banners de Feedback --}}
        @if(session('success'))
            <div class="rounded-xl bg-emerald-500 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="rounded-xl bg-red-600 text-white px-4 py-3 text-xs font-semibold shadow-sm flex items-center justify-between">
                <span>{{ $errors->first() }}</span>
                <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 text-base font-semibold">&times;</button>
            </div>
        @endif

        {{-- Formulário Retrátil de Novo Mês --}}
        <div x-show="createOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="rounded-2xl border border-blue-200 bg-white p-5 sm:p-6 shadow-sm"
             style="display: none;">

            <div class="flex items-center justify-between border-b border-gray-100 pb-3 mb-4">
                <h2 class="text-sm font-bold text-gray-800">Cadastrar Nova Competência / Valor de Moradia</h2>
                <button @click="createOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-xs font-semibold">&times; Fechar</button>
            </div>

            <form action="{{ route('admin.cooperative-housing-fees.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Mês --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Mês de Referência <span class="text-rose-500">*</span>
                        </label>
                        <input type="month" name="month" value="{{ old('month', date('Y-m')) }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                    </div>

                    {{-- Valor --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                            Valor da Taxa (R$) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', '50.00') }}" required
                               class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-gray-100">
                    <button @click="createOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Valor</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Tabela de Competências --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-4 sm:p-5 shadow-xs space-y-4">
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs text-gray-700">
                    <thead class="bg-gray-50 text-gray-600 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-4 py-3">Mês / Competência</th>
                            <th class="px-4 py-3">Valor Cobrado</th>
                            <th class="px-4 py-3 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($cooperativeHousingFees as $fee)
                            <tr class="hover:bg-blue-50/30 transition">
                                <td class="px-4 py-3 font-bold text-gray-900 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        </span>
                                        <span>{{ $fee->month->translatedFormat('F \d\e Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 font-bold text-blue-700 text-sm">
                                    R$ {{ number_format($fee->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="inline-flex items-center gap-1">
                                        <button @click="openEdit({{ json_encode($fee) }}, '{{ route('admin.cooperative-housing-fees.update', $fee) }}')"
                                                type="button"
                                                title="Editar"
                                                class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-50 text-blue-800 hover:bg-blue-100 transition shadow-2xs">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>

                                        <form action="{{ route('admin.cooperative-housing-fees.destroy', $fee) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Remover este mês? Os pagamentos registrados para ele também serão removidos.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    title="Excluir"
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition shadow-2xs">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-gray-400">
                                    Nenhuma competência cadastrada ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($cooperativeHousingFees->hasPages())
                <div class="pt-2">
                    {{ $cooperativeHousingFees->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- Modal de Edição Rápida --}}
    <div x-show="editOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-gray-900/60 backdrop-blur-xs"
         style="display: none;">

        <div @click.outside="editOpen = false"
             class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-gray-100 overflow-hidden flex flex-col">

            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="text-sm font-bold text-gray-900">Editar Valor da Moradia Estudantil</h2>
                <button @click="editOpen = false" type="button" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>

            <form :action="editFee.updateUrl" method="POST" class="p-5 space-y-4">
                @csrf @method('PUT')

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Mês de Referência <span class="text-rose-500">*</span>
                    </label>
                    <input type="month" name="month" x-model="editFee.month" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 mb-1">
                        Valor (R$) <span class="text-rose-500">*</span>
                    </label>
                    <input type="number" step="0.01" min="0" name="amount" x-model="editFee.amount" required
                           class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2 text-xs text-gray-800 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 shadow-2xs">
                </div>

                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button @click="editOpen = false" type="button" class="rounded-xl border border-gray-300 bg-white px-4 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-5 py-2 text-xs font-semibold text-white shadow-xs hover:bg-blue-500 active:scale-95 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Salvar Alterações</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

