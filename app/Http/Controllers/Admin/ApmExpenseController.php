<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmExpense;
use Illuminate\Http\Request;

class ApmExpenseController extends Controller
{
    public function index()
    {
        $apmExpenses = ApmExpense::orderByDesc('due_date')->get();
        return view('admin.apm-expenses.index', compact('apmExpenses'));
    }

    public function create()
    {
        return redirect()->route('admin.apm-expenses.index');
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
        ], [
            'description.required' => 'A descrição da despesa é obrigatória.',
            'amount.required'      => 'O valor da despesa é obrigatório.',
            'due_date.required'    => 'A data de vencimento da conta é obrigatória.',
        ]);

        ApmExpense::create($data);
        return redirect()->route('admin.apm-expenses.index')->with('success', 'Despesa / Saída da APM cadastrada com sucesso!');
    }

    public function edit(ApmExpense $apmExpense)
    {
        return redirect()->route('admin.apm-expenses.index');
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
        ], [
            'description.required' => 'A descrição da despesa é obrigatória.',
            'amount.required'      => 'O valor da despesa é obrigatório.',
            'due_date.required'    => 'A data de vencimento da conta é obrigatória.',
        ]);

        $apmExpense->update($data);
        return redirect()->route('admin.apm-expenses.index')->with('success', 'Despesa da APM atualizada com sucesso!');
    }

    public function destroy(ApmExpense $apmExpense)
    {
        $desc = $apmExpense->description;
        $apmExpense->delete();
        return redirect()->route('admin.apm-expenses.index')->with('success', "Despesa \"{$desc}\" removida com sucesso!");
    }

    public function markPaid(ApmExpense $apmExpense)
    {
        $apmExpense->update(['paid_date' => $apmExpense->paid_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $apmExpense->paid_date ? 'Despesa marcada como paga!' : 'Despesa marcada como pendente.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um registro para a ação em lote.');
        }

        switch ($action) {
            case 'mark_paid':
                ApmExpense::whereIn('id', $ids)->update(['paid_date' => now()->format('Y-m-d')]);
                return back()->with('success', count($ids) . ' despesa(s) marcada(s) como paga(s)!');

            case 'mark_pending':
                ApmExpense::whereIn('id', $ids)->update(['paid_date' => null]);
                return back()->with('success', count($ids) . ' despesa(s) marcada(s) como pendente(s).');

            case 'delete':
                ApmExpense::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' despesa(s) excluída(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
