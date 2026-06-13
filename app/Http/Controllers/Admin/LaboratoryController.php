<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Teacher;
use App\Models\Unit;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LaboratoryController extends Controller
{
    public function index()
    {
        $laboratories = Laboratory::with(['responsible', 'unit'])->orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.laboratories.index', compact('laboratories'));
    }

    public function toggle(Laboratory $laboratory)
    {
        $laboratory->update(['is_active' => !$laboratory->is_active]);
        return back()->with('success', '"' . $laboratory->name . '" ' . ($laboratory->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        $units    = Unit::orderBy('name')->get();
        $courses  = Course::orderBy('title')->get();
        return view('admin.laboratories.form', [
            'laboratory' => new Laboratory(),
            'teachers' => $teachers,
            'units'    => $units,
            'courses'  => $courses,
            'action'   => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'full_description' => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'capacity'         => 'nullable|integer|min:1',
            'photo'            => 'nullable|image|max:4096',
            'responsible_id'   => 'nullable|exists:teachers,id',
            'unit_id'          => 'nullable|exists:units,id',
            'course_id'        => 'nullable|exists:courses,id',
            'is_active'        => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('laboratories', 'public');
        }

        Laboratory::create($data);
        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratório cadastrado com sucesso!');
    }

    public function edit(Laboratory $laboratory)
    {
        $teachers = Teacher::orderBy('name')->get();
        $units    = Unit::orderBy('name')->get();
        $courses  = Course::orderBy('title')->get();
        return view('admin.laboratories.form', compact('laboratory', 'teachers', 'units', 'courses') + ['action' => 'edit']);
    }

    public function update(Request $request, Laboratory $laboratory)
    {
        $data = $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'full_description' => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'capacity'         => 'nullable|integer|min:1',
            'photo'            => 'nullable|image|max:4096',
            'responsible_id'   => 'nullable|exists:teachers,id',
            'unit_id'          => 'nullable|exists:units,id',
            'course_id'        => 'nullable|exists:courses,id',
            'is_active'        => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('photo')) {
            if ($laboratory->photo) {
                Storage::disk('public')->delete($laboratory->photo);
            }
            $data['photo'] = $request->file('photo')->store('laboratories', 'public');
        }

        $laboratory->update($data);
        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratório atualizado!');
    }

    public function destroy(Laboratory $laboratory)
    {
        if ($laboratory->photo) {
            Storage::disk('public')->delete($laboratory->photo);
        }
        $laboratory->delete();
        return redirect()->route('admin.laboratories.index')->with('success', 'Laboratório removido!');
    }
}
