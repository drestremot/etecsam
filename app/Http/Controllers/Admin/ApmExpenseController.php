<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmExpense;
use Illuminate\Http\Request;

class ApmExpenseController extends Controller
{
    public function index()
    {
        $apmExpenses = ApmExpense::orderByDesc('due_date')->paginate(30);
        return view('admin.apm-expenses.index', compact('apmExpenses'));
    }

    public function create()
    {
        return view('admin.apm-expenses.form', ['apmExpense' => new ApmExpense(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|numeric|min:0',
            'due_date'    => 'required|date',
            'paid_date'   => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        ApmExpense::create($data);
        return redirect()->route('admin.apm-expenses.index')->with('success', 'Saída cadastrada com sucesso!');
    }

    public function edit(ApmExpense $apmExpense)
    {
        return view('admin.apm-expenses.form', compact('apmExpense') + ['action' => 'edit']);
    }

    public function update(Request $request, ApmExpense $apmExpense)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|numeric|min:0',
            'due_date'    => 'required|date',
            'paid_date'   => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $apmExpense->update($data);
        return redirect()->route('admin.apm-expenses.index')->with('success', 'Saída atualizada com sucesso!');
    }

    public function destroy(ApmExpense $apmExpense)
    {
        $apmExpense->delete();
        return redirect()->route('admin.apm-expenses.index')->with('success', 'Saída removida!');
    }

    public function markPaid(ApmExpense $apmExpense)
    {
        $apmExpense->update(['paid_date' => $apmExpense->paid_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $apmExpense->paid_date ? 'Saída marcada como paga.' : 'Saída marcada como pendente.');
    }
}
