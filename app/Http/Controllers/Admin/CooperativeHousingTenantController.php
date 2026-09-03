<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeHousingFee;
use App\Models\CooperativeHousingPayment;
use App\Models\CooperativeHousingTenant;
use Illuminate\Http\Request;

class CooperativeHousingTenantController extends Controller
{
    public function index()
    {
        $cooperativeHousingTenants = CooperativeHousingTenant::orderByDesc('is_active')->orderBy('name')->get();
        return view('admin.cooperative-housing-tenants.index', compact('cooperativeHousingTenants'));
    }

    public function toggle(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $cooperativeHousingTenant->update(['is_active' => !$cooperativeHousingTenant->is_active]);
        $statusStr = $cooperativeHousingTenant->is_active ? 'ativado(a)' : 'desativado(a)';
        return back()->with('success', "Morador(a) \"{$cooperativeHousingTenant->name}\" {$statusStr} com sucesso.");
    }

    public function create()
    {
        return redirect()->route('admin.cooperative-housing-tenants.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'room'      => 'nullable|string|max:50',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'O nome do estudante/morador é obrigatório.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        CooperativeHousingTenant::create($data);
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', 'Morador(a) do alojamento cadastrado(a) com sucesso!');
    }

    public function edit(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        return redirect()->route('admin.cooperative-housing-tenants.index');
    }

    public function update(Request $request, CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'room'      => 'nullable|string|max:50',
            'is_active' => 'nullable',
        ], [
            'name.required' => 'O nome do estudante/morador é obrigatório.',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : $cooperativeHousingTenant->is_active;

        $cooperativeHousingTenant->update($data);
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', 'Cadastro do morador atualizado com sucesso!');
    }

    public function destroy(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $name = $cooperativeHousingTenant->name;
        $cooperativeHousingTenant->delete();
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', "Morador(a) \"{$name}\" removido(a) com sucesso!");
    }

    public function dues(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $housingFees = CooperativeHousingFee::orderBy('month')->get();
        $payments = $cooperativeHousingTenant->payments()->pluck('paid', 'cooperative_housing_fee_id');

        return view('admin.cooperative-housing-tenants.dues', [
            'cooperativeHousingTenant' => $cooperativeHousingTenant,
            'housingFees' => $housingFees,
            'payments' => $payments,
        ]);
    }

    public function toggleDue(CooperativeHousingTenant $cooperativeHousingTenant, CooperativeHousingFee $cooperativeHousingFee)
    {
        $payment = CooperativeHousingPayment::firstOrNew([
            'cooperative_housing_tenant_id' => $cooperativeHousingTenant->id,
            'cooperative_housing_fee_id' => $cooperativeHousingFee->id,
        ]);
        $payment->paid = !$payment->paid;
        $payment->paid_at = $payment->paid ? now() : null;
        $payment->save();

        return back()->with('success', 'Mensalidade do alojamento ' . ($payment->paid ? 'marcada como paga!' : 'marcada como pendente.'));
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Selecione pelo menos um morador para a ação em lote.');
        }

        switch ($action) {
            case 'activate':
                CooperativeHousingTenant::whereIn('id', $ids)->update(['is_active' => true]);
                return back()->with('success', count($ids) . ' morador(es) ativado(s) com sucesso!');

            case 'deactivate':
                CooperativeHousingTenant::whereIn('id', $ids)->update(['is_active' => false]);
                return back()->with('success', count($ids) . ' morador(es) desativado(s) com sucesso!');

            case 'delete':
                CooperativeHousingTenant::whereIn('id', $ids)->delete();
                return back()->with('success', count($ids) . ' morador(es) excluído(s) com sucesso!');

            default:
                return back()->with('error', 'Ação em lote inválida.');
        }
    }
}
