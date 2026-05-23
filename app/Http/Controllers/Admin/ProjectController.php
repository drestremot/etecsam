<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['responsible', 'department'])->orderBy('name')->paginate(20);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $teachers    = Teacher::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.projects.form', [
            'project'     => new Project(),
            'teachers'    => $teachers,
            'departments' => $departments,
            'action'      => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'objectives'    => 'nullable|string',
            'status'        => 'required|in:ativo,concluido,pausado',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'image'         => 'nullable|image|max:4096',
            'responsible_id'=> 'nullable|exists:teachers,id',
            'department_id' => 'nullable|exists:departments,id',
            'is_active'     => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        Project::create($data);
        return redirect()->route('admin.projects.index')->with('success', 'Projeto cadastrado com sucesso!');
    }

    public function edit(Project $project)
    {
        $teachers    = Teacher::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();
        return view('admin.projects.form', compact('project', 'teachers', 'departments') + ['action' => 'edit']);
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'objectives'    => 'nullable|string',
            'status'        => 'required|in:ativo,concluido,pausado',
            'start_date'    => 'nullable|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'image'         => 'nullable|image|max:4096',
            'responsible_id'=> 'nullable|exists:teachers,id',
            'department_id' => 'nullable|exists:departments,id',
            'is_active'     => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($project->image) {
                Storage::disk('public')->delete($project->image);
            }
            $data['image'] = $request->file('image')->store('projects', 'public');
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success', 'Projeto atualizado!');
    }

    public function destroy(Project $project)
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Projeto removido!');
    }
}
