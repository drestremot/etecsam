@extends('layouts.operational')

@section('content')
<div class="min-h-screen bg-[#dfe1e5] px-3 sm:px-6 lg:px-8 py-5 sm:py-8 pb-20 sm:pb-8">
    <div class="w-full max-w-[1850px] mx-auto space-y-6">

        <!-- Top Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-gray-500 mb-1">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 transition">Gerenciamento</a>
                    <span>/</span>
                    <a href="{{ route('admin.apm-managers.index') }}" class="hover:text-indigo-600 transition">APM</a>
                    <span>/</span>
                    <span class="text-indigo-600 font-bold">Painel Financeiro</span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-gray-900 flex items-center gap-3">
                    <span class="flex items-center gap-2">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </span>
                        Painel Financeiro da APM
                    </span>
                    <span class="rounded-xl bg-indigo-100 border border-indigo-200 px-2.5 py-0.5 text-xs font-semibold text-indigo-700">
                        {{ \Carbon\Carbon::now()->translatedFormat('F / Y') }}
                    </span>
                </h1>
                <p class="text-xs text-gray-600 mt-0.5 font-normal">
                    Demonstrativo consolidado de receitas, despesas, saldo em caixa, pendências e previsões futuras da APM
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.apm-incomes.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-emerald-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Receitas / Entradas</span>
                </a>

                <a href="{{ route('admin.apm-expenses.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-rose-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    <span>Despesas / Saídas</span>
                </a>

                <a href="{{ route('admin.apm-managers.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-blue-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span>Gestores APM</span>
                </a>

                <a href="{{ route('admin.apm-reports.index') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-purple-600 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-purple-500">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Documentos & Atas</span>
                </a>

                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-gray-900 px-3.5 py-2 text-xs font-semibold text-white shadow-xs transition hover:bg-gray-800">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    <span>Hub</span>
                </a>
            </div>
        </div>

        {{-- Resumo do Mês (KPI Cards) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-5">
            {{-- Entradas --}}
            <div class="rounded-2xl border border-emerald-200 bg-white p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-emerald-800 uppercase tracking-wider">Entradas do Mês</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold tracking-tight text-emerald-700">
                    R$ {{ number_format($totalIncome, 2, ',', '.') }}
                </p>
                <div class="mt-3 flex items-center justify-between text-xs pt-3 border-t border-gray-100">
                    <span class="text-gray-500">Contribuições & Eventos</span>
                    <a href="{{ route('admin.apm-incomes.index') }}" class="font-bold text-emerald-700 hover:underline">Ver entradas &rarr;</a>
                </div>
            </div>

            {{-- Saídas --}}
            <div class="rounded-2xl border border-rose-200 bg-white p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold text-rose-800 uppercase tracking-wider">Saídas do Mês</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-rose-100 text-rose-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold tracking-tight text-rose-700">
                    R$ {{ number_format($totalExpenses, 2, ',', '.') }}
                </p>
                <div class="mt-3 flex items-center justify-between text-xs pt-3 border-t border-gray-100">
                    <span class="text-gray-500">Aquisições & Manutenções</span>
                    <a href="{{ route('admin.apm-expenses.index') }}" class="font-bold text-rose-700 hover:underline">Ver saídas &rarr;</a>
                </div>
            </div>

            {{-- Saldo --}}
            <div class="rounded-2xl border {{ $balance >= 0 ? 'border-indigo-200 bg-white' : 'border-rose-300 bg-rose-50/50' }} p-5 sm:p-6 shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-bold {{ $balance >= 0 ? 'text-indigo-900' : 'text-rose-900' }} uppercase tracking-wider">Saldo Líquido</span>
                    <span class="flex h-8 w-8 items-center justify-center rounded-xl {{ $balance >= 0 ? 'bg-indigo-100 text-indigo-700' : 'bg-rose-200 text-rose-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </span>
                </div>
                <p class="text-2xl sm:text-3xl font-bold tracking-tight {{ $balance >= 0 ? 'text-indigo-700' : 'text-rose-700' }}">
                    R$ {{ number_format($balance, 2, ',', '.') }}
                </p>
                <div class="mt-3 flex items-center justify-between text-xs pt-3 border-t border-gray-100">
                    <span class="text-gray-500">Recebido − Pago no mês</span>
                    <span class="font-bold {{ $balance >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">{{ $balance >= 0 ? 'Superávit' : 'Déficit' }}</span>
                </div>
            </div>
        </div>

        {{-- Seções de Pendências e Previsões --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            {{-- Pendências e Atrasados --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Pendências e Contas Vencidas</h2>
                        <p class="text-[11px] text-gray-500">Lançamentos vencidos que necessitam de regularização</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-rose-100 bg-rose-50/40 p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-rose-800">Saídas Atrasadas</span>
                            <span class="rounded-full bg-rose-200 text-rose-800 px-2 py-0.5 text-[10px] font-bold">{{ $overdueExpenses->count() }} contas</span>
                        </div>
                        <p class="text-xl font-bold text-rose-700">
                            R$ {{ number_format($overdueExpenses->sum('amount'), 2, ',', '.') }}
                        </p>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.apm-expenses.index') }}" class="text-xs font-semibold text-rose-700 hover:underline">Ver contas a pagar &rarr;</a>
                        </div>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50/40 p-4 space-y-1">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-amber-900">Entradas Pendentes</span>
                            <span class="rounded-full bg-amber-200 text-amber-900 px-2 py-0.5 text-[10px] font-bold">{{ $overdueIncomes->count() }} itens</span>
                        </div>
                        <p class="text-xl font-bold text-amber-800">
                            R$ {{ number_format($overdueIncomes->sum('amount'), 2, ',', '.') }}
                        </p>
                        <div class="pt-2 text-right">
                            <a href="{{ route('admin.apm-incomes.index') }}" class="text-xs font-semibold text-amber-800 hover:underline">Ver entradas pendentes &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Previsão Futura --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 sm:p-6 shadow-xs space-y-4">
                <div class="flex items-center gap-2 border-b border-gray-100 pb-3">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-bold text-gray-800">Previsão e Fluxo Futuro</h2>
                        <p class="text-[11px] text-gray-500">Lançamentos agendados para vencimento futuro</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-1">
                        <span class="text-xs font-bold text-gray-700">A Pagar (Saídas Futuras)</span>
                        <p class="text-xl font-bold text-gray-900">
                            R$ {{ number_format($upcomingExpenses, 2, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-gray-500 pt-1">Despesas agendadas</p>
                    </div>

                    <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 space-y-1">
                        <span class="text-xs font-bold text-gray-700">A Receber (Entradas Futuras)</span>
                        <p class="text-xl font-bold text-gray-900">
                            R$ {{ number_format($upcomingIncomes, 2, ',', '.') }}
                        </p>
                        <p class="text-[11px] text-gray-500 pt-1">Contribuições agendadas</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

