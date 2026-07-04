<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::orderBy('name')->paginate(20);
        return view('lab.materials.index', compact('materials'));
    }

    public function create()
    {
        return view('lab.materials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'stock_quantity'   => 'required|integer|min:0',
            'unit'             => 'nullable|string|max:50',
            'patrimony_number' => 'nullable|string|unique:materials,patrimony_number',
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('materials', 'public');
        }

        Material::create($validated);

        return redirect()->route('lab.materials.index')
            ->with('success', 'Material cadastrado com sucesso!');
    }

    public function edit(Material $material)
    {
        return view('lab.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'stock_quantity'   => 'required|integer|min:0',
            'unit'             => 'nullable|string|max:50',
            'patrimony_number' => 'nullable|string|unique:materials,patrimony_number,' . $material->id,
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($material->photo) {
                Storage::disk('public')->delete($material->photo);
            }
            $validated['photo'] = $request->file('photo')->store('materials', 'public');
        }

        $material->update($validated);

        return redirect()->route('lab.materials.index')
            ->with('success', 'Material atualizado com sucesso!');
    }

    public function destroy(Material $material)
    {
        if ($material->photo) {
            Storage::disk('public')->delete($material->photo);
        }
        $material->delete();
        return redirect()->route('lab.materials.index')
            ->with('success', 'Material excluído.');
    }
}
