<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('responsible')->orderByDesc('is_active')->orderBy('name')->paginate(50);
        return view('admin.departments.index', compact('departments'));
    }

    public function toggle(Department $department)
    {
        $department->update(['is_active' => !$department->is_active]);
        return back()->with('success', '"' . $department->name . '" ' . ($department->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.departments.form', ['department' => new Department(), 'teachers' => $teachers, 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'location'       => 'nullable|string|max:255',
            'responsible_id' => 'nullable|exists:teachers,id',
            'is_active'      => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        Department::create($data);
        return redirect()->route('admin.departments.index')->with('success', 'Departamento cadastrado com sucesso!');
    }

    public function edit(Department $department)
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.departments.form', compact('department', 'teachers') + ['action' => 'edit']);
    }

    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:30',
            'location'       => 'nullable|string|max:255',
            'responsible_id' => 'nullable|exists:teachers,id',
            'is_active'      => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $department->update($data);
        return redirect()->route('admin.departments.index')->with('success', 'Departamento atualizado!');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Departamento removido!');
    }
}
