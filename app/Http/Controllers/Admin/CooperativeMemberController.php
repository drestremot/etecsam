<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeMember;
use Illuminate\Http\Request;

class CooperativeMemberController extends Controller
{
    public function index()
    {
        $cooperativeMembers = CooperativeMember::orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.cooperative-members.index', compact('cooperativeMembers'));
    }

    public function toggle(CooperativeMember $cooperativeMember)
    {
        $cooperativeMember->update(['is_active' => !$cooperativeMember->is_active]);
        return back()->with('success', '"' . $cooperativeMember->name . '" ' . ($cooperativeMember->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.cooperative-members.form', ['cooperativeMember' => new CooperativeMember(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'registration_number'  => 'nullable|string|max:100',
        ]);

        CooperativeMember::create($data);
        return redirect()->route('admin.cooperative-members.index')->with('success', 'Cooperado cadastrado com sucesso!');
    }

    public function edit(CooperativeMember $cooperativeMember)
    {
        return view('admin.cooperative-members.form', compact('cooperativeMember') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeMember $cooperativeMember)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'registration_number'  => 'nullable|string|max:100',
        ]);

        $cooperativeMember->update($data);
        return redirect()->route('admin.cooperative-members.index')->with('success', 'Cooperado atualizado com sucesso!');
    }

    public function destroy(CooperativeMember $cooperativeMember)
    {
        $cooperativeMember->delete();
        return redirect()->route('admin.cooperative-members.index')->with('success', 'Cooperado removido!');
    }
}
