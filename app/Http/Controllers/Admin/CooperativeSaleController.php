<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeSale;
use Illuminate\Http\Request;

class CooperativeSaleController extends Controller
{
    public function index()
    {
        $cooperativeSales = CooperativeSale::orderByDesc('sale_date')->paginate(30);
        return view('admin.cooperative-sales.index', compact('cooperativeSales'));
    }

    public function create()
    {
        return view('admin.cooperative-sales.form', ['cooperativeSale' => new CooperativeSale(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'sale_date'     => 'required|date',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        CooperativeSale::create($data);
        return redirect()->route('admin.cooperative-sales.index')->with('success', 'Venda cadastrada com sucesso!');
    }

    public function edit(CooperativeSale $cooperativeSale)
    {
        return view('admin.cooperative-sales.form', compact('cooperativeSale') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeSale $cooperativeSale)
    {
        $data = $request->validate([
            'description'   => 'required|string|max:255',
            'category'      => 'nullable|string|max:100',
            'amount'        => 'required|numeric|min:0',
            'sale_date'     => 'required|date',
            'due_date'      => 'required|date',
            'received_date' => 'nullable|date',
            'notes'         => 'nullable|string',
        ]);

        $cooperativeSale->update($data);
        return redirect()->route('admin.cooperative-sales.index')->with('success', 'Venda atualizada com sucesso!');
    }

    public function destroy(CooperativeSale $cooperativeSale)
    {
        $cooperativeSale->delete();
        return redirect()->route('admin.cooperative-sales.index')->with('success', 'Venda removida!');
    }

    public function markReceived(CooperativeSale $cooperativeSale)
    {
        $cooperativeSale->update(['received_date' => $cooperativeSale->received_date ? null : now()->format('Y-m-d')]);
        return back()->with('success', $cooperativeSale->received_date ? 'Venda marcada como recebida.' : 'Venda marcada como pendente.');
    }
}
