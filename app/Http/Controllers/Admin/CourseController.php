<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['unit', 'technicalCoordinators'])
                         ->orderByDesc('is_active')->orderBy('title')->paginate(50);
        return view('admin.courses.index', compact('courses'));
    }

    public function toggle(Course $course)
    {
        $course->update(['is_active' => !$course->is_active]);
        return back()->with('success', '"' . $course->title . '" ' . ($course->is_active ? 'ativado' : 'desativado') . '.');
    }

    public function create()
    {
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        $units    = Unit::orderBy('name')->get();
        return view('admin.courses.form', [
            'course'   => new Course(),
            'teachers' => $teachers,
            'units'    => $units,
            'action'   => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'                    => 'required|string|max:255',
            'type'                     => 'required|string|max:255',
            'description'              => 'required|string',
            'content'                  => 'nullable|string',
            'schedule'                 => 'nullable|string',
            'image'                    => 'nullable|image|max:4096',
            'unit_id'                  => 'nullable|exists:units,id',
            'is_active'                => 'boolean',
            'technical_coordinators'   => 'nullable|array',
            'technical_coordinators.*' => 'exists:teachers,id',
            'decentralized_coordinators'   => 'nullable|array',
            'decentralized_coordinators.*' => 'exists:teachers,id',
        ]);

        $data['slug']      = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course = Course::create($data);
        $this->syncCoordinators($course, $request);

        return redirect()->route('admin.courses.index')->with('success', 'Curso cadastrado com sucesso!');
    }

    public function edit(Course $course)
    {
        $course->load(['technicalCoordinators', 'decentralizedCoordinators']);
        $teachers = Teacher::where('is_active', true)->orderBy('name')->get();
        $units    = Unit::orderBy('name')->get();
        return view('admin.courses.form', compact('course', 'teachers', 'units') + ['action' => 'edit']);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'                    => 'required|string|max:255',
            'type'                     => 'required|string|max:255',
            'description'              => 'required|string',
            'content'                  => 'nullable|string',
            'schedule'                 => 'nullable|string',
            'image'                    => 'nullable|image|max:4096',
            'unit_id'                  => 'nullable|exists:units,id',
            'is_active'                => 'boolean',
            'technical_coordinators'   => 'nullable|array',
            'technical_coordinators.*' => 'exists:teachers,id',
            'decentralized_coordinators'   => 'nullable|array',
            'decentralized_coordinators.*' => 'exists:teachers,id',
        ]);

        $data['slug']      = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);
        $this->syncCoordinators($course, $request);

        return redirect()->route('admin.courses.index')->with('success', 'Curso atualizado!');
    }

    public function destroy(Course $course)
    {
        if ($course->image) {
            Storage::disk('public')->delete($course->image);
        }
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Curso removido!');
    }

    private function syncCoordinators(Course $course, Request $request): void
    {
        $course->coordinators()->detach();

        foreach ($request->input('technical_coordinators', []) as $i => $id) {
            $course->coordinators()->attach($id, ['role' => 'tecnico', 'order' => $i]);
        }

        foreach ($request->input('decentralized_coordinators', []) as $i => $id) {
            $course->coordinators()->attach($id, ['role' => 'descentralizado', 'order' => $i]);
        }
    }
}
