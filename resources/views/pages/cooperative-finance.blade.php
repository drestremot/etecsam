@extends('layouts.app')

@section('content')

{{-- Hero --}}
<div class="bg-etec-dark text-white py-14 border-b-4 border-etec-accent">
    <div class="container mx-auto px-4 flex items-center gap-6">
        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-8 h-8 text-etec-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm6 0V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v10a2 2 0 002 2h2a2 2 0 002-2zm6 0V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 002 2h2a2 2 0 002-2z"/>
            </svg>
        </div>
        <div>
            <p class="text-etec-accent text-xs font-bold uppercase tracking-widest mb-1">Cooperativa Escola</p>
            <h1 class="text-3xl font-bold mb-1">Transparência Financeira</h1>
            <p class="text-gray-300 text-sm">Resumo de entradas, despesas e previsões do mês atual</p>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 py-12 space-y-10">

    <div class="mb-2">
        <a href="{{ route('cooperative') }}" class="inline-flex items-center gap-1.5 text-sm text-etec-medium dark:text-etec-accent hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Voltar para a Cooperativa Escola
        </a>
    </div>

    {{-- Resumo do mes --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Resumo do Mês</h2>
        <div class="grid md:grid-cols-3 gap-5">
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
                <p class="text-xs font-bold text-etec-light uppercase tracking-widest mb-2">Entradas</p>
                <p class="text-2xl font-bold text-white">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
                <div class="mt-3 text-xs text-green-100 space-y-0.5">
                    <p>Vendas: R$ {{ number_format($salesIncome, 2, ',', '.') }}</p>
                    <p>Mensalidades Cooperados: R$ {{ number_format($memberDuesIncome, 2, ',', '.') }}</p>
                    <p>Mensalidades Moradia: R$ {{ number_format($housingDuesIncome, 2, ',', '.') }}</p>
                </div>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
                <p class="text-xs font-bold text-etec-light uppercase tracking-widest mb-2">Despesas</p>
                <p class="text-2xl font-bold text-white">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</p>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
                <p class="text-xs font-bold text-etec-light uppercase tracking-widest mb-2">Saldo</p>
                <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-etec-accent' : 'text-red-300' }}">R$ {{ number_format($balance, 2, ',', '.') }}</p>
                <p class="text-xs text-green-100 mt-3">Entradas − despesas pagas este mês</p>
            </div>
        </div>
    </div>

    {{-- Atrasados --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-medium pl-3">Pendências</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 text-center">
                <p class="text-2xl font-bold text-white">{{ $overdueExpenses->count() }}</p>
                <p class="text-xs text-green-100 mt-1">Despesas atrasadas</p>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 text-center">
                <p class="text-2xl font-bold text-white">{{ $overdueSales->count() }}</p>
                <p class="text-xs text-green-100 mt-1">Vendas a receber</p>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 text-center">
                <p class="text-2xl font-bold text-white">{{ $overdueMembers }}</p>
                <p class="text-xs text-green-100 mt-1">Cooperados com mensalidade pendente</p>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-5 text-center">
                <p class="text-2xl font-bold text-white">{{ $overdueTenants }}</p>
                <p class="text-xs text-green-100 mt-1">Moradores com mensalidade pendente</p>
            </div>
        </div>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Por privacidade, apenas a quantidade é exibida — nomes individuais ficam restritos à administração.</p>
    </div>

    {{-- Previsao futura --}}
    <div>
        <h2 class="text-xl font-bold text-etec-dark dark:text-white mb-6 border-l-4 border-etec-accent pl-3">Previsão Futura</h2>
        <div class="grid md:grid-cols-2 gap-5">
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
                <p class="text-xs font-bold text-etec-light uppercase tracking-widest mb-2">A Pagar (despesas futuras)</p>
                <p class="text-xl font-bold text-white">R$ {{ number_format($upcomingExpenses, 2, ',', '.') }}</p>
            </div>
            <div class="bg-etec-main rounded-2xl shadow-sm border border-etec-dark/30 dark:border-white/10 p-6">
                <p class="text-xs font-bold text-etec-light uppercase tracking-widest mb-2">A Receber (vendas futuras)</p>
                <p class="text-xl font-bold text-white">R$ {{ number_format($upcomingSales, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
