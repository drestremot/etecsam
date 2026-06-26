<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeMonthlyFee;
use Illuminate\Http\Request;

class CooperativeMonthlyFeeController extends Controller
{
    public function index()
    {
        $cooperativeMonthlyFees = CooperativeMonthlyFee::orderByDesc('month')->paginate(24);
        return view('admin.cooperative-monthly-fees.index', compact('cooperativeMonthlyFees'));
    }

    public function create()
    {
        return view('admin.cooperative-monthly-fees.form', ['cooperativeMonthlyFee' => new CooperativeMonthlyFee(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month'  => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        // Normaliza para o primeiro dia do mes, evitando duplicidade por dia diferente
        $data['month'] = \Carbon\Carbon::parse($data['month'])->startOfMonth()->format('Y-m-d');

        if (CooperativeMonthlyFee::where('month', $data['month'])->exists()) {
            return back()->withInput()->withErrors(['month' => 'Já existe um valor cadastrado para este mês.']);
        }

        CooperativeMonthlyFee::create($data);
        return redirect()->route('admin.cooperative-monthly-fees.index')->with('success', 'Valor da mensalidade cadastrado com sucesso!');
    }

    public function edit(CooperativeMonthlyFee $cooperativeMonthlyFee)
    {
        return view('admin.cooperative-monthly-fees.form', compact('cooperativeMonthlyFee') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeMonthlyFee $cooperativeMonthlyFee)
    {
        $data = $request->validate([
            'month'  => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['month'] = \Carbon\Carbon::parse($data['month'])->startOfMonth()->format('Y-m-d');

        $cooperativeMonthlyFee->update($data);
        return redirect()->route('admin.cooperative-monthly-fees.index')->with('success', 'Valor da mensalidade atualizado!');
    }

    public function destroy(CooperativeMonthlyFee $cooperativeMonthlyFee)
    {
        $cooperativeMonthlyFee->delete();
        return redirect()->route('admin.cooperative-monthly-fees.index')->with('success', 'Mensalidade removida!');
    }
}
