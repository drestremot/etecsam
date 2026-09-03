<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeExpense;
use Illuminate\Http\Request;

class CooperativeExpenseController extends Controller
{
    public function index()
    {
        $cooperativeExpenses = CooperativeExpense::orderByDesc('due_date')->get();
        return view('admin.cooperative-expenses.index', compact('cooperativeExpenses'));
    }

    public function create()
    {
        return redirect()->route('admin.cooperative-expenses.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|numeric|min:0.01',
            'due_date'    => 'required|date',
            'paid_date'   => 'nullable|date',
            'notes'       => 'nullable|string',
        ], [
            'description.required' => 'A descrição da despesa/insumo é obrigatória.',
            'amount.required'      => 'Informe o valor da despesa.',
            'amount.min'           => 'O valor deve ser maior que zero.',
            'due_date.required'    => 'A data de vencimento é obrigatória.',
        ]);

        CooperativeExpense::create($data);
        return redirect()->route('admin.cooperative-expenses.index')->with('success', 'Despesa da Cooperativa registrada com sucesso!');
    }

    public function edit(CooperativeExpense $cooperativeExpense)
    {
        return redirect()->route('admin.cooperative-expenses.index');
    }

    public function update(Request $request, CooperativeExpense $cooperativeExpense)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'category'    => 'nullable|string|max:100',
            'amount'      => 'required|numeric|min:0.01',
            'due_date'    => 'required|date',
            'paid_date'   => 'nullable|date',
            'notes'       => 'nullable|string',
        ], [
            'description.required' => 'A descrição da despesa/insumo é obrigatória.',
            'amount.required'      => 'Informe o valor da despesa.',
            'amount.min'           => 'O valor deve ser maior que zero.',
            'due_date.required'    => 'A data de vencimento é obrigatória.',
        ]);

        $cooperativeExpense->update($data);
        return redirect()->route('admin.cooperative-expenses.index')->with('success', 'Despesa da Cooperativa atualizada com sucesso!');
    }

    public function destroy(CooperativeExpense $cooperativeExpense)
    {
        $desc = $cooperativeExpense->description;
        $cooperativeExpense->delete();
        return redirect()->route('admin.cooperative-expenses.index')->with('success', "Despesa \"{$desc}\" removida!");
    }

    public function markPaid(CooperativeExpense $cooperativeExpense)
    {
        $cooperativeExpense->update(['paid_date' => $cooperativeExpense->paid_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $cooperativeExpense->paid_date ? 'Despesa marcada como paga com sucesso!' : 'Despesa alterada para pendente.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos uma despesa para a ação em lote.');
        }

        switch ($action) {
            case 'mark_paid':
                CooperativeExpense::whereIn('id', $ids)->update(['paid_date' => now()->format('Y-m-d')]);
                return back()->with('success', count($ids) . ' despesa(s) marcada(s) como paga(s)!');

            case 'mark_pending':
                CooperativeExpense::whereIn('id', $ids)->update(['paid_date' => null]);
                return back()->with('success', count($ids) . ' despesa(s) marcada(s) como pendente(s)!');

            case 'delete':
                CooperativeExpense::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' despesa(s) excluída(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
