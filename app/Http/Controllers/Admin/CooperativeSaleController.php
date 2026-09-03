<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeSale;
use Illuminate\Http\Request;

class CooperativeSaleController extends Controller
{
    public function index()
    {
        $cooperativeSales = CooperativeSale::orderByDesc('sale_date')->get();
        return view('admin.cooperative-sales.index', compact('cooperativeSales'));
    }

    public function create()
    {
        return redirect()->route('admin.cooperative-sales.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0.01',
            'sale_date'     => 'required|date',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ], [
            'description.required' => 'A descrição do produto/venda é obrigatória.',
            'amount.required'      => 'Informe o valor da venda.',
            'amount.min'           => 'O valor deve ser maior que zero.',
            'sale_date.required'   => 'A data da venda é obrigatória.',
            'due_date.required'    => 'A data de vencimento é obrigatória.',
        ]);

        CooperativeSale::create($data);
        return redirect()->route('admin.cooperative-sales.index')->with('success', 'Venda da Escola Fazenda registrada com sucesso!');
    }

    public function edit(CooperativeSale $cooperativeSale)
    {
        return redirect()->route('admin.cooperative-sales.index');
    }

    public function update(Request $request, CooperativeSale $cooperativeSale)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0.01',
            'sale_date'     => 'required|date',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ], [
            'description.required' => 'A descrição do produto/venda é obrigatória.',
            'amount.required'      => 'Informe o valor da venda.',
            'amount.min'           => 'O valor deve ser maior que zero.',
            'sale_date.required'   => 'A data da venda é obrigatória.',
            'due_date.required'    => 'A data de vencimento é obrigatória.',
        ]);

        $cooperativeSale->update($data);
        return redirect()->route('admin.cooperative-sales.index')->with('success', 'Registro de venda atualizado!');
    }

    public function destroy(CooperativeSale $cooperativeSale)
    {
        $desc = $cooperativeSale->description;
        $cooperativeSale->delete();
        return redirect()->route('admin.cooperative-sales.index')->with('success', "Venda \"{$desc}\" removida!");
    }

    public function markReceived(CooperativeSale $cooperativeSale)
    {
        $cooperativeSale->update(['received_date' => $cooperativeSale->received_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $cooperativeSale->received_date ? 'Venda marcada como recebida com sucesso!' : 'Venda alterada para pendente.');
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos uma venda para a ação em lote.');
        }

        switch ($action) {
            case 'mark_received':
                CooperativeSale::whereIn('id', $ids)->update(['received_date' => now()->format('Y-m-d')]);
                return back()->with('success', count($ids) . ' venda(s) marcada(s) como recebida(s)!');

            case 'mark_pending':
                CooperativeSale::whereIn('id', $ids)->update(['received_date' => null]);
                return back()->with('success', count($ids) . ' venda(s) marcada(s) como pendente(s)!');

            case 'delete':
                CooperativeSale::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' venda(s) excluída(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
