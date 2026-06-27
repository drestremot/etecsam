<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmIncome;
use Illuminate\Http\Request;

class ApmIncomeController extends Controller
{
    public function index()
    {
        $apmIncomes = ApmIncome::orderByDesc('due_date')->paginate(30);
        return view('admin.apm-incomes.index', compact('apmIncomes'));
    }

    public function create()
    {
        return view('admin.apm-incomes.form', ['apmIncome' => new ApmIncome(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        ApmIncome::create($data);
        return redirect()->route('admin.apm-incomes.index')->with('success', 'Entrada cadastrada com sucesso!');
    }

    public function edit(ApmIncome $apmIncome)
    {
        return view('admin.apm-incomes.form', compact('apmIncome') + ['action' => 'edit']);
    }

    public function update(Request $request, ApmIncome $apmIncome)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        $apmIncome->update($data);
        return redirect()->route('admin.apm-incomes.index')->with('success', 'Entrada atualizada com sucesso!');
    }

    public function destroy(ApmIncome $apmIncome)
    {
        $apmIncome->delete();
        return redirect()->route('admin.apm-incomes.index')->with('success', 'Entrada removida!');
    }

    public function markReceived(ApmIncome $apmIncome)
    {
        $apmIncome->update(['received_date' => $apmIncome->received_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $apmIncome->received_date ? 'Entrada marcada como recebida.' : 'Entrada marcada como pendente.');
    }
}
