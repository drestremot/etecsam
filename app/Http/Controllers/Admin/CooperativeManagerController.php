<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CooperativeManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CooperativeManagerController extends Controller
{
    public function index()
    {
        $cooperativeManagers = CooperativeManager::orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.cooperative-managers.index', compact('cooperativeManagers'));
    }

    public function toggle(CooperativeManager $cooperativeManager)
    {
        $cooperativeManager->update(['is_active' => !$cooperativeManager->is_active]);
        return back()->with('success', '"' . $cooperativeManager->name . '" ' . ($cooperativeManager->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        return view('admin.cooperative-managers.form', ['cooperativeManager' => new CooperativeManager(), 'action' => 'create']);
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
            $data['photo'] = $request->file('photo')->store('cooperative-managers', 'public');
        }

        CooperativeManager::create($data);
        return redirect()->route('admin.cooperative-managers.index')->with('success', 'Gestor cadastrado com sucesso!');
    }

    public function edit(CooperativeManager $cooperativeManager)
    {
        return view('admin.cooperative-managers.form', compact('cooperativeManager') + ['action' => 'edit']);
    }

    public function update(Request $request, CooperativeManager $cooperativeManager)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'photo' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($cooperativeManager->photo) {
                Storage::disk('public')->delete($cooperativeManager->photo);
            }
            $data['photo'] = $request->file('photo')->store('cooperative-managers', 'public');
        }

        $cooperativeManager->update($data);
        return redirect()->route('admin.cooperative-managers.index')->with('success', 'Gestor atualizado com sucesso!');
    }

    public function destroy(CooperativeManager $cooperativeManager)
    {
        if ($cooperativeManager->photo) {
            Storage::disk('public')->delete($cooperativeManager->photo);
        }
        $cooperativeManager->delete();
        return redirect()->route('admin.cooperative-managers.index')->with('success', 'Gestor removido!');
    }
}
