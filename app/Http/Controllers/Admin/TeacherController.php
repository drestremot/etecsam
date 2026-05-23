<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::orderBy('name')->paginate(20);
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.form', ['teacher' => new Teacher(), 'action' => 'create']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:30',
            'lattes_url'=> 'nullable|url|max:500',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        Teacher::create($data);
        return redirect()->route('admin.teachers.index')->with('success', 'Professor cadastrado com sucesso!');
    }

    public function edit(Teacher $teacher)
    {
        return view('admin.teachers.form', compact('teacher') + ['action' => 'edit']);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'role'      => 'required|string|max:255',
            'specialty' => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:255',
            'phone'     => 'nullable|string|max:30',
            'lattes_url'=> 'nullable|url|max:500',
            'photo'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $data['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($data);
        return redirect()->route('admin.teachers.index')->with('success', 'Professor atualizado com sucesso!');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }
        $teacher->delete();
        return redirect()->route('admin.teachers.index')->with('success', 'Professor removido!');
    }
}
