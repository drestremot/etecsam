<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkSchedule::with(['user', 'unit', 'course', 'subject'])
            ->orderBy('day_of_week')
            ->orderBy('start_time');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('day_of_week') && $request->day_of_week !== '') {
            $query->where('day_of_week', (int) $request->day_of_week);
        }

        if ($request->filled('schedule_type')) {
            $query->where('schedule_type', $request->schedule_type);
        }

        $schedules = $query->get();

        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email', 'role']);
        $units = Unit::where('is_active', true)->orderBy('name')->get(['id', 'name', 'city']);
        $courses = Course::where('is_active', true)->orderBy('title')->get(['id', 'title', 'type', 'unit_id']);

        $stats = [
            'total_schedules' => WorkSchedule::where('is_active', true)->count(),
            'scheduled_users' => WorkSchedule::where('is_active', true)->distinct('user_id')->count(),
            'active_units'    => Unit::where('is_active', true)->count(),
            'active_courses'  => Course::where('is_active', true)->count(),
        ];

        return view('admin.work-schedules.index', compact('schedules', 'users', 'units', 'courses', 'stats'));
    }

    public function create(Request $request)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get(['id', 'title', 'type', 'unit_id']);
        $daysList = WorkSchedule::getDaysList();
        $dayColorConfigs = WorkSchedule::getDayColorConfig();

        $subjects = Subject::with('course:id,title')
            ->orderBy('name')
            ->get(['id', 'course_id', 'name', 'semester', 'workload']);

        $initialUserId = $request->input('user_id', $users->first()?->id);

        $usersData = $users->map(function ($u) {
            $assignedSubs = $u->assigned_subjects->map(function ($s) {
                return [
                    'id'           => $s->id,
                    'name'         => $s->name,
                    'course_id'    => $s->course_id,
                    'course_title' => $s->course?->title ?? '',
                    'semester'     => $s->semester ?? '',
                    'workload'     => $s->workload ?? '',
                ];
            })->values();

            return [
                'id'                 => $u->id,
                'name'               => $u->name,
                'role'               => $u->role ?? 'Docente',
                'schedule_role_type' => $u->schedule_role_type,
                'teacher_color'      => $u->teacher_color,
                'assigned_subjects'  => $assignedSubs,
            ];
        })->values();

        $allUserSchedules = WorkSchedule::where('is_active', true)
            ->with(['unit:id,name,city', 'course:id,title', 'subject:id,name'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('user_id')
            ->map(function ($schedules) {
                return $schedules->map(function ($s) {
                    return [
                        'id'                => $s->id,
                        'day_of_week'       => (int) $s->day_of_week,
                        'unit_id'           => (int) $s->unit_id,
                        'unit_name'         => $s->unit?->name ?? 'Unidade',
                        'course_id'         => $s->course_id ? (int) $s->course_id : null,
                        'course_name'       => $s->course_name ?? ($s->course?->title ?? ''),
                        'subject_id'        => $s->subject_id ? (int) $s->subject_id : null,
                        'start_time'        => substr($s->start_time, 0, 5),
                        'end_time'          => substr($s->end_time, 0, 5),
                        'shift_name'        => $s->shift_name ?? '',
                        'subject_name'      => $s->subject_name ?? '',
                        'class_name'        => $s->class_name ?? '',
                        'division'          => $s->division ?? '',
                        'classroom'         => $s->classroom ?? '',
                        'schedule_type'     => $s->schedule_type ?? 'class',
                        'break_start_time'  => $s->break_start_time ? substr($s->break_start_time, 0, 5) : null,
                        'break_end_time'    => $s->break_end_time ? substr($s->break_end_time, 0, 5) : null,
                        'tolerance_minutes' => (int) $s->tolerance_minutes,
                    ];
                })->values();
            });

        return view('admin.work-schedules.form', [
            'action'           => 'create',
            'schedule'         => new WorkSchedule(['tolerance_minutes' => 15, 'is_active' => true, 'schedule_type' => 'class']),
            'users'            => $users,
            'usersData'        => $usersData,
            'units'            => $units,
            'courses'          => $courses,
            'daysList'         => $daysList,
            'dayColorConfigs'  => $dayColorConfigs,
            'subjects'         => $subjects,
            'initialUserId'    => $initialUserId,
            'allUserSchedules' => $allUserSchedules,
        ]);
    }

    public function userSchedules(User $user)
    {
        $schedules = WorkSchedule::where('user_id', $user->id)
            ->with(['unit:id,name,city', 'course:id,title', 'subject:id,name'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $assignedSubjects = $user->assigned_subjects->map(function ($s) {
            return [
                'id'           => $s->id,
                'name'         => $s->name,
                'course_id'    => $s->course_id,
                'course_title' => $s->course?->title ?? '',
                'semester'     => $s->semester ?? '',
                'workload'     => $s->workload ?? '',
            ];
        })->values();

        return response()->json([
            'schedules'         => $schedules,
            'assigned_subjects' => $assignedSubjects,
        ]);
    }

    public function store(Request $request)
    {
        // Modo construtor interativo via JSON / Array
        if ($request->filled('schedules_json') || $request->has('items')) {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            $items = $request->filled('schedules_json')
                ? json_decode($request->input('schedules_json'), true)
                : $request->input('items', []);

            if (empty($items) || !is_array($items)) {
                return back()->withInput()->withErrors(['items' => 'Adicione pelo menos um horário na grade antes de salvar.']);
            }

            $userId = (int) $request->user_id;
            $user = User::findOrFail($userId);
            $savedCount = 0;

            \DB::transaction(function () use ($userId, $items, &$savedCount) {
                // Sincroniza a grade completa substituindo registros anteriores
                WorkSchedule::where('user_id', $userId)->delete();

                foreach ($items as $item) {
                    $day = (int) ($item['day_of_week'] ?? 1);
                    $unitId = (int) ($item['unit_id'] ?? null);
                    if (!$unitId) continue;

                    $startTime = substr($item['start_time'] ?? '07:10', 0, 5) . ':00';
                    $endTime = substr($item['end_time'] ?? '12:35', 0, 5) . ':00';
                    $shiftName = !empty($item['shift_name']) ? trim($item['shift_name']) : null;
                    $courseId = !empty($item['course_id']) ? (int) $item['course_id'] : null;
                    $subjectId = !empty($item['subject_id']) ? (int) $item['subject_id'] : null;
                    $courseName = !empty($item['course_name']) ? trim($item['course_name']) : null;
                    $subjectName = !empty($item['subject_name']) ? trim($item['subject_name']) : null;
                    $className = !empty($item['class_name']) ? trim($item['class_name']) : null;
                    $division = !empty($item['division']) ? trim($item['division']) : null;
                    $classroom = !empty($item['classroom']) ? trim($item['classroom']) : null;
                    $scheduleType = !empty($item['schedule_type']) ? trim($item['schedule_type']) : 'class';
                    $breakStart = !empty($item['break_start_time']) ? substr($item['break_start_time'], 0, 5) . ':00' : null;
                    $breakEnd = !empty($item['break_end_time']) ? substr($item['break_end_time'], 0, 5) . ':00' : null;
                    $tolerance = isset($item['tolerance_minutes']) ? (int) $item['tolerance_minutes'] : 15;

                    if ($courseId && empty($courseName)) {
                        $courseObj = Course::find($courseId);
                        $courseName = $courseObj?->title;
                    }

                    WorkSchedule::create([
                        'user_id'           => $userId,
                        'unit_id'           => $unitId,
                        'course_id'         => $courseId,
                        'subject_id'        => $subjectId,
                        'course_name'       => $courseName,
                        'day_of_week'       => $day,
                        'start_time'        => $startTime,
                        'end_time'          => $endTime,
                        'shift_name'        => $shiftName,
                        'subject_name'      => $subjectName,
                        'class_name'        => $className,
                        'division'          => $division,
                        'classroom'         => $classroom,
                        'schedule_type'     => $scheduleType,
                        'break_start_time'  => $breakStart,
                        'break_end_time'    => $breakEnd,
                        'tolerance_minutes' => $tolerance,
                        'is_active'         => true,
                    ]);

                    $savedCount++;
                }
            });

            return redirect()->route('admin.work-schedules.create', ['user_id' => $userId])
                ->with('success', "Grade de horários de {$user->name} salva com sucesso! ({$savedCount} horário(s) ativo(s)).");
        }

        // Modo formulário simples
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unit_id'           => 'required|exists:units,id',
            'days_of_week'      => 'required|array|min:1',
            'days_of_week.*'    => 'integer|between:0,6',
            'course_id'         => 'nullable|exists:courses,id',
            'subject_id'        => 'nullable|exists:subjects,id',
            'course_name'       => 'nullable|string|max:150',
            'shift_name'        => 'nullable|string|max:100',
            'subject_name'      => 'nullable|string|max:255',
            'class_name'        => 'nullable|string|max:100',
            'division'          => 'nullable|string|max:20',
            'classroom'         => 'nullable|string|max:100',
            'schedule_type'     => 'nullable|string|in:class,coordination,administrative',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i|after:start_time',
            'break_start_time'  => 'nullable|date_format:H:i',
            'break_end_time'    => 'nullable|date_format:H:i|after:break_start_time',
            'tolerance_minutes' => 'nullable|integer|min:0|max:60',
        ]);

        $tolerance = $request->input('tolerance_minutes', 15);
        $scheduleType = $request->input('schedule_type', 'class');
        $courseId = $request->input('course_id');
        $courseName = $request->input('course_name');
        if ($courseId && empty($courseName)) {
            $courseObj = Course::find($courseId);
            $courseName = $courseObj?->title;
        }

        foreach ($request->days_of_week as $day) {
            WorkSchedule::updateOrCreate(
                [
                    'user_id'     => $request->user_id,
                    'unit_id'     => $request->unit_id,
                    'day_of_week' => $day,
                    'start_time'  => $request->start_time . ':00',
                ],
                [
                    'course_id'         => $courseId,
                    'subject_id'        => $request->input('subject_id'),
                    'course_name'       => $courseName,
                    'shift_name'        => $request->shift_name,
                    'subject_name'      => $request->subject_name,
                    'class_name'        => $request->class_name,
                    'division'          => $request->division,
                    'classroom'         => $request->classroom,
                    'schedule_type'     => $scheduleType,
                    'end_time'          => $request->end_time . ':00',
                    'break_start_time'  => $request->break_start_time ? $request->break_start_time . ':00' : null,
                    'break_end_time'    => $request->break_end_time ? $request->break_end_time . ':00' : null,
                    'tolerance_minutes' => $tolerance,
                    'is_active'         => true,
                ]
            );
        }

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Grade de horários cadastrada com sucesso!');
    }

    public function edit(WorkSchedule $workSchedule)
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get(['id', 'title', 'type', 'unit_id']);
        $daysList = WorkSchedule::getDaysList();
        $subjects = Subject::with('course:id,title')->orderBy('name')->get(['id', 'course_id', 'name', 'semester']);

        return view('admin.work-schedules.form', [
            'action'    => 'edit',
            'schedule'  => $workSchedule,
            'users'     => $users,
            'units'     => $units,
            'courses'   => $courses,
            'daysList'  => $daysList,
            'subjects'  => $subjects,
        ]);
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unit_id'           => 'required|exists:units,id',
            'day_of_week'       => 'required|integer|between:0,6',
            'course_id'         => 'nullable|exists:courses,id',
            'subject_id'        => 'nullable|exists:subjects,id',
            'course_name'       => 'nullable|string|max:150',
            'shift_name'        => 'nullable|string|max:100',
            'subject_name'      => 'nullable|string|max:255',
            'class_name'        => 'nullable|string|max:100',
            'division'          => 'nullable|string|max:20',
            'classroom'         => 'nullable|string|max:100',
            'schedule_type'     => 'nullable|string|in:class,coordination,administrative',
            'start_time'        => 'required',
            'end_time'          => 'required',
            'break_start_time'  => 'nullable',
            'break_end_time'    => 'nullable',
            'tolerance_minutes' => 'nullable|integer|min:0|max:60',
            'is_active'         => 'boolean',
        ]);

        $courseId = $request->input('course_id');
        $courseName = $request->input('course_name');
        if ($courseId && empty($courseName)) {
            $courseObj = Course::find($courseId);
            $courseName = $courseObj?->title;
        }

        $workSchedule->update([
            'user_id'           => $request->user_id,
            'unit_id'           => $request->unit_id,
            'course_id'         => $courseId,
            'subject_id'        => $request->input('subject_id'),
            'course_name'       => $courseName,
            'day_of_week'       => $request->day_of_week,
            'shift_name'        => $request->shift_name,
            'subject_name'      => $request->subject_name,
            'class_name'        => $request->class_name,
            'division'          => $request->division,
            'classroom'         => $request->classroom,
            'schedule_type'     => $request->input('schedule_type', 'class'),
            'start_time'        => substr($request->start_time, 0, 5) . ':00',
            'end_time'          => substr($request->end_time, 0, 5) . ':00',
            'break_start_time'  => $request->break_start_time ? substr($request->break_start_time, 0, 5) . ':00' : null,
            'break_end_time'    => $request->break_end_time ? substr($request->break_end_time, 0, 5) . ':00' : null,
            'tolerance_minutes' => $request->input('tolerance_minutes', 15),
            'is_active'         => $request->has('is_active'),
        ]);

        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Horário de trabalho atualizado com sucesso!');
    }

    public function destroy(WorkSchedule $workSchedule)
    {
        $workSchedule->delete();
        return redirect()->route('admin.work-schedules.index')
            ->with('success', 'Horário removido da grade.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete',
            'ids'    => 'required|array|min:1',
            'ids.*'  => 'exists:work_schedules,id',
        ]);

        $count = count($request->ids);
        if ($request->action === 'delete') {
            WorkSchedule::whereIn('id', $request->ids)->delete();
            return back()->with('success', "{$count} horário(s) removido(s) da grade!");
        }

        return back();
    }

    /**
     * Tela de visualização e impressão da Grade Horária por Unidade Escolar e Curso
     */
    public function printSchedule(Request $request)
    {
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $courses = Course::where('is_active', true)->orderBy('title')->get(['id', 'title', 'type', 'unit_id']);
        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'role']);

        $selectedUnitId = $request->input('unit_id', $units->first()?->id);
        $selectedUnit = $units->firstWhere('id', $selectedUnitId) ?? $units->first();

        $selectedCourseId = $request->input('course_id', '');
        $selectedTeacherId = $request->input('teacher_id', '');
        $selectedShift = $request->input('shift_name', '');
        $selectedType = $request->input('schedule_type', '');

        $query = WorkSchedule::with(['user', 'unit', 'course', 'subject'])
            ->where('is_active', true);

        if ($selectedUnit) {
            $query->where('unit_id', $selectedUnit->id);
        }

        if (!empty($selectedCourseId)) {
            $query->where('course_id', $selectedCourseId);
        }

        if (!empty($selectedTeacherId)) {
            $query->where('user_id', $selectedTeacherId);
        }

        if (!empty($selectedShift)) {
            $query->where('shift_name', 'like', "%{$selectedShift}%");
        }

        if (!empty($selectedType)) {
            $query->where('schedule_type', $selectedType);
        }

        $schedules = $query->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $daysList = WorkSchedule::getDaysList();
        // Apenas Segunda a Sábado para a grade principal (e Domingo se houver registro)
        $hasSunday = $schedules->contains('day_of_week', 0);
        $activeDays = [1, 2, 3, 4, 5, 6];
        if ($hasSunday) {
            $activeDays[] = 0;
        }

        $dayColorConfigs = WorkSchedule::getDayColorConfig();

        // Professores presentes nesta grade para a legenda de cores
        $teachersInSchedule = $schedules->pluck('user')
            ->filter()
            ->unique('id')
            ->sortBy('name')
            ->values();

        // Cursos presentes nesta grade
        $coursesInSchedule = $schedules->pluck('course')
            ->filter()
            ->unique('id')
            ->sortBy('title')
            ->values();

        // Identifica os slots de horários existentes ordenados por início
        $timeSlots = $schedules->map(function ($s) {
            return [
                'start' => substr($s->start_time, 0, 5),
                'end'   => substr($s->end_time, 0, 5),
                'key'   => substr($s->start_time, 0, 5) . ' às ' . substr($s->end_time, 0, 5),
            ];
        })->unique('key')->sortBy('start')->values();

        // Agrupa por dia da semana
        $schedulesByDay = $schedules->groupBy('day_of_week');

        $selectedCourse = $courses->firstWhere('id', $selectedCourseId);
        $selectedTeacher = $users->firstWhere('id', $selectedTeacherId);

        return view('admin.work-schedules.print', [
            'units'              => $units,
            'courses'            => $courses,
            'users'              => $users,
            'selectedUnit'       => $selectedUnit,
            'selectedCourseId'   => $selectedCourseId,
            'selectedCourse'     => $selectedCourse,
            'selectedTeacherId'  => $selectedTeacherId,
            'selectedTeacher'    => $selectedTeacher,
            'selectedShift'      => $selectedShift,
            'selectedType'       => $selectedType,
            'schedules'          => $schedules,
            'schedulesByDay'     => $schedulesByDay,
            'activeDays'         => $activeDays,
            'daysList'           => $daysList,
            'dayColorConfigs'    => $dayColorConfigs,
            'teachersInSchedule' => $teachersInSchedule,
            'coursesInSchedule'  => $coursesInSchedule,
            'timeSlots'          => $timeSlots,
        ]);
    }
}