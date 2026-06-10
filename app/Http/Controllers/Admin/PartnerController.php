<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::orderBy('order')->get();
        return view('admin.partners.index', compact('partners'));
    }

    public function create()
    {
        $partner = new Partner();
        $title = 'Novo Parceiro';
        return view('admin.partners.form', compact('partner', 'title'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'website'     => 'nullable|url|max:255',
            'logo'        => 'nullable|image|max:2048',
            'description' => 'nullable|string|max:500',
            'order'       => 'nullable|integer|min:0',
            'active'      => 'nullable',
        ]);

        $data['active'] = $request->has('active') ? 1 : 0;
        $data['order']  = $request->input('order', 0);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        Partner::create($data);
        return redirect()->route('admin.partners.index')->with('success', 'Parceiro cadastrado com sucesso!');
    }

    public function edit(Partner $partner)
    {
        $title = 'Editar Parceiro: ' . $partner->name;
        return view('admin.partners.form', compact('partner', 'title'));
    }

    public function update(Request $request, Partner $partner)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'website'     => 'nullable|url|max:255',
            'logo'        => 'nullable|image|max:2048',
            'description' => 'nullable|string|max:500',
            'order'       => 'nullable|integer|min:0',
            'active'      => 'nullable',
        ]);

        $data['active'] = $request->has('active') ? 1 : 0;
        $data['order']  = $request->input('order', 0);

        if ($request->hasFile('logo')) {
            if ($partner->logo) Storage::disk('public')->delete($partner->logo);
            $data['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($data);
        return redirect()->route('admin.partners.index')->with('success', 'Parceiro atualizado com sucesso!');
    }

    public function destroy(Partner $partner)
    {
        if ($partner->logo) Storage::disk('public')->delete($partner->logo);
        $partner->delete();
        return redirect()->route('admin.partners.index')->with('success', 'Parceiro removido.');
    }
}
