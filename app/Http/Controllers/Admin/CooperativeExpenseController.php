<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeExpense;
use Illuminate\Http\Request;

class CooperativeExpenseController extends Controller
{
    public function index()
    {
        $cooperativeExpenses = CooperativeExpense::orderByDesc('due_date')->paginate(30);
        return view('admin.cooperative-expenses.index', compact('cooperativeExpenses'));
    }

    public function create()
    {
        return view('admin.cooperative-expenses.form', ['cooperativeExpense' => new CooperativeExpense(), 'action' => 'create']);
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

        CooperativeExpense::create($data);
        return redirect()->route('admin.cooperative-expenses.index')->with('success', 'Despesa cadastrada com sucesso!');
    }

    public function edit(CooperativeExpense $cooperativeExpense)
    {
        return view('admin.cooperative-expenses.form', compact('cooperativeExpense') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeExpense $cooperativeExpense)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|numeric|min:0',
            'due_date'    => 'required|date',
            'paid_date'   => 'nullable|date',
            'notes'       => 'nullable|string',
        ]);

        $cooperativeExpense->update($data);
        return redirect()->route('admin.cooperative-expenses.index')->with('success', 'Despesa atualizada com sucesso!');
    }

    public function destroy(CooperativeExpense $cooperativeExpense)
    {
        $cooperativeExpense->delete();
        return redirect()->route('admin.cooperative-expenses.index')->with('success', 'Despesa removida!');
    }

    public function markPaid(CooperativeExpense $cooperativeExpense)
    {
        $cooperativeExpense->update(['paid_date' => $cooperativeExpense->paid_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $cooperativeExpense->paid_date ? 'Despesa marcada como paga.' : 'Despesa marcada como pendente.');
    }
}
