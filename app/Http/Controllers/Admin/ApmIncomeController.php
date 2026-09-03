<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmIncome;
use Illuminate\Http\Request;

class ApmIncomeController extends Controller
{
    public function index()
    {
        $apmIncomes = ApmIncome::orderByDesc('due_date')->get();
        return view('admin.apm-incomes.index', compact('apmIncomes'));
    }

    public function create()
    {
        return redirect()->route('admin.apm-incomes.index');
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
        ], [
            'description.required' => 'A descrição da receita é obrigatória.',
            'amount.required'      => 'O valor da receita é obrigatório.',
            'due_date.required'    => 'A data de previsão/vencimento é obrigatória.',
        ]);

        ApmIncome::create($data);
        return redirect()->route('admin.apm-incomes.index')->with('success', 'Receita / Entrada da APM cadastrada com sucesso!');
    }

    public function edit(ApmIncome $apmIncome)
    {
        return redirect()->route('admin.apm-incomes.index');
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
        ], [
            'description.required' => 'A descrição da receita é obrigatória.',
            'amount.required'      => 'O valor da receita é obrigatório.',
            'due_date.required'    => 'A data de previsão/vencimento é obrigatória.',
        ]);

        $apmIncome->update($data);
        return redirect()->route('admin.apm-incomes.index')->with('success', 'Receita da APM atualizada com sucesso!');
    }

    public function destroy(ApmIncome $apmIncome)
    {
        $desc = $apmIncome->description;
        $apmIncome->delete();
        return redirect()->route('admin.apm-incomes.index')->with('success', "Receita \"{$desc}\" removida com sucesso!");
    }

    public function markReceived(ApmIncome $apmIncome)
    {
        $apmIncome->update(['received_date' => $apmIncome->received_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $apmIncome->received_date ? 'Receita marcada como recebida!' : 'Receita marcada como pendente.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um registro para a ação em lote.');
        }

        switch ($action) {
            case 'mark_received':
                ApmIncome::whereIn('id', $ids)->update(['received_date' => now()->format('Y-m-d')]);
                return back()->with('success', count($ids) . ' receita(s) marcada(s) como recebida(s)!');

            case 'mark_pending':
                ApmIncome::whereIn('id', $ids)->update(['received_date' => null]);
                return back()->with('success', count($ids) . ' receita(s) marcada(s) como pendente(s).');

            case 'delete':
                ApmIncome::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' receita(s) excluída(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
