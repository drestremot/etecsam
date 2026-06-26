<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeExpense;
use App\Models\CooperativeHousingFee;
use App\Models\CooperativeHousingPayment;
use App\Models\CooperativeHousingTenant;
use App\Models\CooperativeMember;
use App\Models\CooperativeMonthlyFee;
use App\Models\CooperativeDue;
use App\Models\CooperativeSale;
use Illuminate\Support\Carbon;

class CooperativeDashboardController extends Controller
{
    public function index()
    {
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // ── Entradas do mes (vendas recebidas + mensalidades pagas) ──
        $salesIncome = CooperativeSale::whereBetween('received_date', [$monthStart, $monthEnd])->sum('amount');

        $memberDuesIncome = CooperativeDue::where('paid', true)
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->join('cooperative_monthly_fees', 'cooperative_monthly_fees.id', '=', 'cooperative_dues.cooperative_monthly_fee_id')
            ->sum('cooperative_monthly_fees.amount');

        $housingDuesIncome = CooperativeHousingPayment::where('paid', true)
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->join('cooperative_housing_fees', 'cooperative_housing_fees.id', '=', 'cooperative_housing_payments.cooperative_housing_fee_id')
            ->sum('cooperative_housing_fees.amount');

        $totalIncome = $salesIncome + $memberDuesIncome + $housingDuesIncome;

        // ── Despesas do mes (pagas) ──
        $totalExpenses = CooperativeExpense::whereBetween('paid_date', [$monthStart, $monthEnd])->sum('amount');

        $balance = $totalIncome - $totalExpenses;

        // ── Atrasados ──
        $overdueExpenses = CooperativeExpense::whereNull('paid_date')->where('due_date', '<', today())->get();
        $overdueSales = CooperativeSale::whereNull('received_date')->where('due_date', '<', today())->get();

        $totalMonthlyFees = CooperativeMonthlyFee::where('month', '<=', $monthStart)->count();
        $overdueMembers = $totalMonthlyFees > 0
            ? CooperativeMember::where('is_active', true)->get()->reject(fn ($m) => $m->isUpToDate())->count()
            : 0;

        $totalHousingFees = CooperativeHousingFee::where('month', '<=', $monthStart)->count();
        $overdueTenants = $totalHousingFees > 0
            ? CooperativeHousingTenant::where('is_active', true)->get()->reject(fn ($t) => $t->isUpToDate())->count()
            : 0;

        // ── Previsao futura (lancamentos com data futura ainda nao resolvidos) ──
        $upcomingExpenses = CooperativeExpense::whereNull('paid_date')->where('due_date', '>=', today())->sum('amount');
        $upcomingSales = CooperativeSale::whereNull('received_date')->where('due_date', '>=', today())->sum('amount');

        return view('admin.cooperative-dashboard.index', [
            'totalIncome' => $totalIncome,
            'salesIncome' => $salesIncome,
            'memberDuesIncome' => $memberDuesIncome,
            'housingDuesIncome' => $housingDuesIncome,
            'totalExpenses' => $totalExpenses,
            'balance' => $balance,
            'overdueExpenses' => $overdueExpenses,
            'overdueSales' => $overdueSales,
            'overdueMembers' => $overdueMembers,
            'overdueTenants' => $overdueTenants,
            'upcomingExpenses' => $upcomingExpenses,
            'upcomingSales' => $upcomingSales,
        ]);
    }
}
