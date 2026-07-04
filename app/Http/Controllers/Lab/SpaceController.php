<?php

namespace App\Http\Controllers\Lab;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;

class SpaceController extends Controller
{
    public function index()
    {
        $spaces = Space::with(['auxiliar', 'laboratory'])->orderBy('name')->get();
        return view('lab.spaces.index', compact('spaces'));
    }

    public function create()
    {
        $auxiliares   = User::role('Auxiliar')->where('is_active', true)->orderBy('name')->get();
        $laboratories = Laboratory::where('is_active', true)->orderBy('name')->get();
        return view('lab.spaces.create', compact('auxiliares', 'laboratories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'auxiliar_id'    => 'nullable|exists:users,id',
            'laboratory_id'  => 'nullable|exists:laboratories,id',
        ]);

        Space::create($request->only('name', 'description', 'auxiliar_id', 'laboratory_id'));

        return redirect()->route('lab.spaces.index')
            ->with('success', 'Espaço didático cadastrado com sucesso!');
    }

    public function edit(Space $space)
    {
        $auxiliares   = User::role('Auxiliar')->where('is_active', true)->orderBy('name')->get();
        $laboratories = Laboratory::where('is_active', true)->orderBy('name')->get();
        return view('lab.spaces.edit', compact('space', 'auxiliares', 'laboratories'));
    }

    public function update(Request $request, Space $space)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'auxiliar_id'    => 'nullable|exists:users,id',
            'laboratory_id'  => 'nullable|exists:laboratories,id',
        ]);

        $space->update($request->only('name', 'description', 'auxiliar_id', 'laboratory_id'));

        return redirect()->route('lab.spaces.index')
            ->with('success', 'Laboratório atualizado com sucesso!');
    }

    public function destroy(Space $space)
    {
        $space->delete();
        return redirect()->route('lab.spaces.index')
            ->with('success', 'Espaço excluído.');
    }
}
