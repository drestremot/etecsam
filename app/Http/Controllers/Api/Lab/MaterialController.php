<?php

namespace App\Http\Controllers\Api\Lab;

use App\Http\Controllers\Controller;
use App\Http\Resources\MaterialResource;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function index()
    {
        return MaterialResource::collection(Material::orderBy('name')->get());
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

        $material = Material::create($validated);

        return (new MaterialResource($material))
            ->additional(['message' => 'Material cadastrado com sucesso!'])
            ->response()->setStatusCode(201);
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'stock_quantity'   => 'required|integer|min:0',
            'unit'             => 'nullable|string|max:50',
            'patrimony_number' => 'nullable|string|unique:materials,patrimony_number,'.$material->id,
            'photo'            => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($material->photo) {
                Storage::disk('public')->delete($material->photo);
            }
            $validated['photo'] = $request->file('photo')->store('materials', 'public');
        }

        $material->update($validated);

        return (new MaterialResource($material))
            ->additional(['message' => 'Material atualizado com sucesso!']);
    }

    public function destroy(Material $material)
    {
        if ($material->photo) {
            Storage::disk('public')->delete($material->photo);
        }
        $material->delete();

        return response()->json(['message' => 'Material excluído.']);
    }
}
