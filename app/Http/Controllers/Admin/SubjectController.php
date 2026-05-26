<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Course $course)
    {
        $subjects = $course->subjects()->with('teacher')->orderBy('name')->get();
        return view('admin.subjects.index', compact('course', 'subjects'));
    }

    public function create(Course $course)
    {
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.subjects.form', compact('course', 'teachers'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'workload'   => 'required|integer|min:1|max:9999',
            'ptd_file'   => 'nullable|url|max:500',
            'semester'   => 'nullable|string|max:50',
        ]);

        $course->subjects()->create($data);

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Disciplina adicionada com sucesso.');
    }

    public function edit(Course $course, Subject $subject)
    {
        abort_unless($subject->course_id === $course->id, 404);
        $teachers = Teacher::orderBy('name')->get();
        return view('admin.subjects.form', compact('course', 'subject', 'teachers'));
    }

    public function update(Request $request, Course $course, Subject $subject)
    {
        abort_unless($subject->course_id === $course->id, 404);

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'workload'   => 'required|integer|min:1|max:9999',
            'ptd_file'   => 'nullable|url|max:500',
            'semester'   => 'nullable|string|max:50',
        ]);

        $subject->update($data);

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Disciplina atualizada com sucesso.');
    }

    public function destroy(Course $course, Subject $subject)
    {
        abort_unless($subject->course_id === $course->id, 404);
        $subject->delete();

        return redirect()
            ->route('admin.courses.subjects.index', $course)
            ->with('success', 'Disciplina removida.');
    }
}
