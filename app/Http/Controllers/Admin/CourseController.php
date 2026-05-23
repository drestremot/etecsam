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
        $courses = Course::with(['unit', 'technicalCoordinator'])->orderBy('title')->paginate(20);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $teachers = Teacher::orderBy('name')->get();
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
            'title'                       => 'required|string|max:255',
            'type'                        => 'required|string|max:255',
            'description'                 => 'required|string',
            'content'                     => 'nullable|string',
            'schedule'                    => 'nullable|string',
            'image'                       => 'nullable|image|max:4096',
            'unit_id'                     => 'nullable|exists:units,id',
            'technical_coordinator_id'    => 'nullable|exists:teachers,id',
            'decentralized_coordinator_id'=> 'nullable|exists:teachers,id',
            'is_active'                   => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create($data);
        return redirect()->route('admin.courses.index')->with('success', 'Curso cadastrado com sucesso!');
    }

    public function edit(Course $course)
    {
        $teachers = Teacher::orderBy('name')->get();
        $units    = Unit::orderBy('name')->get();
        return view('admin.courses.form', compact('course', 'teachers', 'units') + ['action' => 'edit']);
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'                       => 'required|string|max:255',
            'type'                        => 'required|string|max:255',
            'description'                 => 'required|string',
            'content'                     => 'nullable|string',
            'schedule'                    => 'nullable|string',
            'image'                       => 'nullable|image|max:4096',
            'unit_id'                     => 'nullable|exists:units,id',
            'technical_coordinator_id'    => 'nullable|exists:teachers,id',
            'decentralized_coordinator_id'=> 'nullable|exists:teachers,id',
            'is_active'                   => 'boolean',
        ]);

        $data['slug'] = Str::slug($data['title']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($course->image) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);
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
}
