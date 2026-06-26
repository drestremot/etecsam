@extends('admin.layouts.app')
@section('page-title', 'Financeiro da Cooperativa')

@section('content')
<div class="space-y-6">

    {{-- Resumo do mes --}}
    <div class="grid md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Entradas do Mês</p>
            <p class="text-2xl font-bold text-green-600">R$ {{ number_format($totalIncome, 2, ',', '.') }}</p>
            <div class="mt-3 text-xs text-gray-500 space-y-0.5">
                <p>Vendas: R$ {{ number_format($salesIncome, 2, ',', '.') }}</p>
                <p>Mensalidades Cooperados: R$ {{ number_format($memberDuesIncome, 2, ',', '.') }}</p>
                <p>Mensalidades Moradia: R$ {{ number_format($housingDuesIncome, 2, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Despesas do Mês</p>
            <p class="text-2xl font-bold text-red-600">R$ {{ number_format($totalExpenses, 2, ',', '.') }}</p>
            <a href="{{ route('admin.cooperative-expenses.index') }}" class="text-xs text-indigo-600 hover:underline mt-3 inline-block">Ver despesas</a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Saldo do Mês</p>
            <p class="text-2xl font-bold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">R$ {{ number_format($balance, 2, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-3">Entradas − Despesas pagas este mês</p>
        </div>
    </div>

    {{-- Atrasados --}}
    <div>
        <h2 class="text-lg font-bold text-gray-800 mb-4">⚠ Atrasados</h2>
        <div class="grid md:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Despesas Atrasadas</p>
                <p class="text-xl font-bold text-red-600">{{ $overdueExpenses->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">R$ {{ number_format($overdueExpenses->sum('amount'), 2, ',', '.') }}</p>
                <a href="{{ route('admin.cooperative-expenses.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Ver</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Vendas a Receber</p>
                <p class="text-xl font-bold text-red-600">{{ $overdueSales->count() }}</p>
                <p class="text-xs text-gray-500 mt-1">R$ {{ number_format($overdueSales->sum('amount'), 2, ',', '.') }}</p>
                <a href="{{ route('admin.cooperative-sales.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Ver</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Cooperados Inadimplentes</p>
                <p class="text-xl font-bold text-red-600">{{ $overdueMembers }}</p>
                <a href="{{ route('admin.cooperative-members.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Ver</a>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Moradores Inadimplentes</p>
                <p class="text-xl font-bold text-red-600">{{ $overdueTenants }}</p>
                <a href="{{ route('admin.cooperative-housing-tenants.index') }}" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Ver</a>
            </div>
        </div>
    </div>

    {{-- Previsao futura --}}
    <div>
        <h2 class="text-lg font-bold text-gray-800 mb-4">📅 Previsão Futura</h2>
        <p class="text-xs text-gray-400 mb-4">Lançamentos já cadastrados com data futura, ainda não pagos/recebidos.</p>
        <div class="grid md:grid-cols-2 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">A Pagar (Despesas Futuras)</p>
                <p class="text-xl font-bold text-gray-700">R$ {{ number_format($upcomingExpenses, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">A Receber (Vendas Futuras)</p>
                <p class="text-xl font-bold text-gray-700">R$ {{ number_format($upcomingSales, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
