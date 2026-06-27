<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApmManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApmManagerController extends Controller
{
    public function index()
    {
        $apmManagers = ApmManager::orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.apm-managers.index', compact('apmManagers'));
    }

    public function toggle(ApmManager $apmManager)
    {
        $apmManager->update(['is_active' => !$apmManager->is_active]);
        return back()->with('success', '"' . $apmManager->name . '" ' . ($apmManager->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.apm-managers.form', ['apmManager' => new ApmManager(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('apm-managers', 'public');
        }

        ApmManager::create($data);
        return redirect()->route('admin.apm-managers.index')->with('success', 'Membro cadastrado com sucesso!');
    }

    public function edit(ApmManager $apmManager)
    {
        return view('admin.apm-managers.form', compact('apmManager') + ['action' => 'edit']);
    }

    public function update(Request $request, ApmManager $apmManager)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($apmManager->photo) {
                Storage::disk('public')->delete($apmManager->photo);
            }
            $data['photo'] = $request->file('photo')->store('apm-managers', 'public');
        }

        $apmManager->update($data);
        return redirect()->route('admin.apm-managers.index')->with('success', 'Membro atualizado com sucesso!');
    }

    public function destroy(ApmManager $apmManager)
    {
        if ($apmManager->photo) {
            Storage::disk('public')->delete($apmManager->photo);
        }
        $apmManager->delete();
        return redirect()->route('admin.apm-managers.index')->with('success', 'Membro removido!');
    }
}
