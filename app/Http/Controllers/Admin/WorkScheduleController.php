<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\User;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;

class WorkScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = WorkSchedule::with(['user', 'unit'])->orderBy('day_of_week')->orderBy('start_time');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->day_of_week);
        }

        $schedules = $query->get();

        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email', 'role']);
        $units = Unit::where('is_active', true)->orderBy('name')->get(['id', 'name', 'city']);

        $stats = [
            'total_schedules' => WorkSchedule::where('is_active', true)->count(),
            'scheduled_users' => WorkSchedule::where('is_active', true)->distinct('user_id')->count(),
            'active_units'    => Unit::where('is_active', true)->count(),
        ];

        return view('admin.work-schedules.index', compact('schedules', 'users', 'units', 'stats'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        $units = Unit::where('is_active', true)->orderBy('name')->get();
        $daysList = WorkSchedule::getDaysList();

        return view('admin.work-schedules.form', [
            'action'    => 'create',
            'schedule'  => new WorkSchedule(['tolerance_minutes' => 15, 'is_active' => true]),
            'users'     => $users,
            'units'     => $units,
            'daysList'  => $daysList,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unit_id'           => 'required|exists:units,id',
            'days_of_week'      => 'required|array|min:1',
            'days_of_week.*'    => 'integer|between:0,6',
            'shift_name'        => 'nullable|string|max:100',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i|after:start_time',
            'break_start_time'  => 'nullable|date_format:H:i',
            'break_end_time'    => 'nullable|date_format:H:i|after:break_start_time',
            'tolerance_minutes' => 'nullable|integer|min:0|max:60',
        ]);

        $tolerance = $request->input('tolerance_minutes', 15);

        foreach ($request->days_of_week as $day) {
            WorkSchedule::updateOrCreate(
                [
                    'user_id'     => $request->user_id,
                    'unit_id'     => $request->unit_id,
                    'day_of_week' => $day,
                    'start_time'  => $request->start_time . ':00',
                ],
                [
                    'shift_name'        => $request->shift_name,
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
        $daysList = WorkSchedule::getDaysList();

        return view('admin.work-schedules.form', [
            'action'    => 'edit',
            'schedule'  => $workSchedule,
            'users'     => $users,
            'units'     => $units,
            'daysList'  => $daysList,
        ]);
    }

    public function update(Request $request, WorkSchedule $workSchedule)
    {
        $request->validate([
            'user_id'           => 'required|exists:users,id',
            'unit_id'           => 'required|exists:units,id',
            'day_of_week'       => 'required|integer|between:0,6',
            'shift_name'        => 'nullable|string|max:100',
            'start_time'        => 'required',
            'end_time'          => 'required',
            'break_start_time'  => 'nullable',
            'break_end_time'    => 'nullable',
            'tolerance_minutes' => 'nullable|integer|min:0|max:60',
            'is_active'         => 'boolean',
        ]);

        $workSchedule->update([
            'user_id'           => $request->user_id,
            'unit_id'           => $request->unit_id,
            'day_of_week'       => $request->day_of_week,
            'shift_name'        => $request->shift_name,
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
}

