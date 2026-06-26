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
        $cooperativeHousingTenants = CooperativeHousingTenant::orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.cooperative-housing-tenants.index', compact('cooperativeHousingTenants'));
    }

    public function toggle(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $cooperativeHousingTenant->update(['is_active' => !$cooperativeHousingTenant->is_active]);
        return back()->with('success', '"' . $cooperativeHousingTenant->name . '" ' . ($cooperativeHousingTenant->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.cooperative-housing-tenants.form', ['cooperativeHousingTenant' => new CooperativeHousingTenant(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
        ]);

        CooperativeHousingTenant::create($data);
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', 'Morador cadastrado com sucesso!');
    }

    public function edit(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        return view('admin.cooperative-housing-tenants.form', compact('cooperativeHousingTenant') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'room' => 'nullable|string|max:50',
        ]);

        $cooperativeHousingTenant->update($data);
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', 'Morador atualizado com sucesso!');
    }

    public function destroy(CooperativeHousingTenant $cooperativeHousingTenant)
    {
        $cooperativeHousingTenant->delete();
        return redirect()->route('admin.cooperative-housing-tenants.index')->with('success', 'Morador removido!');
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

        return back()->with('success', 'Pagamento ' . ($payment->paid ? 'marcado como pago' : 'marcado como pendente') . '.');
    }
}
