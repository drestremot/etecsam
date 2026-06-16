<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('coordinator')->orderByDesc('is_active')->orderBy('city')->orderBy('name')->paginate(20);
        return view('admin.units.index', compact('units'));
    }

    public function toggle(Unit $unit)
    {
        $unit->update(['is_active' => !$unit->is_active]);
        return back()->with('success', '"' . $unit->name . '" ' . ($unit->is_active ? 'ativada' : 'desativada') . '.');
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.units.form', ['unit' => new Unit(), 'teachers' => $teachers, 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'image'          => 'nullable|image|max:4096',
            'coordinator_id' => 'nullable|exists:teachers,id',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        Unit::create($data);
        return redirect()->route('admin.units.index')->with('success', 'Unidade cadastrada com sucesso!');
    }

    public function edit(Unit $unit)
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.units.form', compact('unit', 'teachers') + ['action' => 'edit']);
    }

    public function update(Request $request, Unit $unit)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'city'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:500',
            'image'          => 'nullable|image|max:4096',
            'coordinator_id' => 'nullable|exists:teachers,id',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            if ($unit->image) {
                Storage::disk('public')->delete($unit->image);
            }
            $data['image'] = $request->file('image')->store('units', 'public');
        }

        $unit->update($data);
        return redirect()->route('admin.units.index')->with('success', 'Unidade atualizada!');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->image) {
            Storage::disk('public')->delete($unit->image);
        }
        $unit->delete();
        return redirect()->route('admin.units.index')->with('success', 'Unidade removida!');
    }
}
