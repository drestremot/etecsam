<?php

namespace App\Support;

use App\Models\ApmExpense;
use App\Models\ApmIncome;
use Illuminate\Support\Carbon;

/**
 * Calcula o resumo financeiro da APM (entradas, despesas, atrasados,
 * previsao futura), no mesmo formato usado pelo dashboard da Cooperativa.
 */
class ApmFinanceSummary
{
    public static function compute(): array
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        $totalIncome = ApmIncome::whereBetween('received_date', [$monthStart, $monthEnd])->sum('amount');
        $totalExpenses = ApmExpense::whereBetween('paid_date', [$monthStart, $monthEnd])->sum('amount');
        $balance = $totalIncome - $totalExpenses;

        $overdueExpenses = ApmExpense::whereNull('paid_date')->where('due_date', '<', today())->get();
        $overdueIncomes = ApmIncome::whereNull('received_date')->where('due_date', '<', today())->get();

        $upcomingExpenses = ApmExpense::whereNull('paid_date')->where('due_date', '>=', today())->sum('amount');
        $upcomingIncomes = ApmIncome::whereNull('received_date')->where('due_date', '>=', today())->sum('amount');

        return [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance,
            'overdueExpenses' => $overdueExpenses,
            'overdueIncomes' => $overdueIncomes,
            'upcomingExpenses' => $upcomingExpenses,
            'upcomingIncomes' => $upcomingIncomes,
        ];
    }
}
