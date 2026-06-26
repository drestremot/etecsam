<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeHousingFee;
use Illuminate\Http\Request;

class CooperativeHousingFeeController extends Controller
{
    public function index()
    {
        $cooperativeHousingFees = CooperativeHousingFee::orderByDesc('month')->paginate(24);
        return view('admin.cooperative-housing-fees.index', compact('cooperativeHousingFees'));
    }

    public function create()
    {
        return view('admin.cooperative-housing-fees.form', ['cooperativeHousingFee' => new CooperativeHousingFee(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'month'  => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['month'] = \Carbon\Carbon::parse($data['month'])->startOfMonth()->format('Y-m-d');

        if (CooperativeHousingFee::where('month', $data['month'])->exists()) {
            return back()->withInput()->withErrors(['month' => 'Já existe um valor cadastrado para este mês.']);
        }

        CooperativeHousingFee::create($data);
        return redirect()->route('admin.cooperative-housing-fees.index')->with('success', 'Valor da mensalidade cadastrado com sucesso!');
    }

    public function edit(CooperativeHousingFee $cooperativeHousingFee)
    {
        return view('admin.cooperative-housing-fees.form', compact('cooperativeHousingFee') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeHousingFee $cooperativeHousingFee)
    {
        $data = $request->validate([
            'month'  => 'required|date',
            'amount' => 'required|numeric|min:0',
        ]);

        $data['month'] = \Carbon\Carbon::parse($data['month'])->startOfMonth()->format('Y-m-d');

        $cooperativeHousingFee->update($data);
        return redirect()->route('admin.cooperative-housing-fees.index')->with('success', 'Valor da mensalidade atualizado!');
    }

    public function destroy(CooperativeHousingFee $cooperativeHousingFee)
    {
        $cooperativeHousingFee->delete();
        return redirect()->route('admin.cooperative-housing-fees.index')->with('success', 'Mensalidade removida!');
    }
}
