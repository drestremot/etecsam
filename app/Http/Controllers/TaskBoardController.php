<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use App\Models\LabReservation;
use App\Models\Space;
use App\Models\Material;
use App\Models\MedicalCertificate;
use App\Models\LegalLeave;
use App\Models\LegalLeaveRequest;
use App\Models\VanReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskBoardController extends Controller
{
    public function dashboard(Request $request)
    {
        $query = Task::query();

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('assigned_to', $request->user_id)
                    ->orWhere('responsible_id', $request->user_id)
                    ->orWhere('created_by', $request->user_id);
            });
        }

        $stats = [
            'total' => (clone $query)->count(),
            'atribuida' => (clone $query)->where('status', 'atribuida')->count(),
            'em_andamento' => (clone $query)->where('status', 'em_andamento')->count(),
            'em_execucao' => (clone $query)->where('status', 'em_execucao')->count(),
            'devolvida' => (clone $query)->where('status', 'devolvida')->count(),
            'concluida' => (clone $query)->where('status', 'concluida')->count(),
        ];

        $resQuery = LabReservation::query();
        if (Auth::user() && !Auth::user()->is_admin && !Auth::user()->hasRole('Coordenador') && !Auth::user()->hasRole('Auxiliar')) {
            $resQuery->where('user_id', Auth::id());
        }

        $resStats = [
            'total'                  => (clone $resQuery)->count(),
            'pendentes'              => (clone $resQuery)->where('status', 'pre_alocada')->count(),
            'aprovadas'              => (clone $resQuery)->where('status', 'aprovada')->count(),
            'em_aula'                => (clone $resQuery)->where('status', 'em_execucao')->count(),
            'em_execucao'            => (clone $resQuery)->where('status', 'em_execucao')->count(),
            'aguardando_conferencia' => (clone $resQuery)->where('status', 'aguardando_conferencia')->count(),
            'conferencia'            => (clone $resQuery)->where('status', 'aguardando_conferencia')->count(),
            'conferidas'             => (clone $resQuery)->where('status', 'conferida')->count(),
            'concluidas'             => (clone $resQuery)->where('status', 'concluida')->count(),
            'totalSpaces'            => Space::count(),
            'totalMaterials'         => Material::count(),
        ];

        $recentTasks = Task::with(['department', 'assignee', 'responsible'])
            ->latest()
            ->take(5)
            ->get();

        $recentReservations = (clone $resQuery)->with(['space', 'user'])
            ->orderBy('reservation_date', 'desc')
            ->take(5)
            ->get();

        $certQuery = MedicalCertificate::query();
        if (Auth::user() && !Auth::user()->canManageMedicalCertificates()) {
            $certQuery->where('user_id', Auth::id());
        }

        $certStats = [
            'total' => (clone $certQuery)->count(),
            'pendentes' => (clone $certQuery)->where('status', 'pendente')->count(),
            'homologados' => (clone $certQuery)->where('status', 'homologado')->count(),
            'afastados_hoje' => (clone $certQuery)
                ->where('start_date', '<=', today())
                ->where('end_date', '>=', today())
                ->where('status', 'homologado')
                ->count(),
        ];

        $leaveQuery = LegalLeave::query();
        $leaveReqQuery = LegalLeaveRequest::query();
        if (Auth::user() && !Auth::user()->canManageMedicalCertificates() && !Auth::user()->hasRole('Coordenador')) {
            $leaveQuery->where('user_id', Auth::id());
            $leaveReqQuery->where('user_id', Auth::id());
        }

        $legalLeaveStats = [
            'total_granted' => (clone $leaveQuery)->sum('days_granted'),
            'total_used' => (clone $leaveQuery)->sum('days_used'),
            'total_remaining' => (clone $leaveQuery)->sum('days_remaining'),
            'pending_requests' => (clone $leaveReqQuery)->where('status', 'pendente')->count(),
        ];

        $vanQuery = VanReservation::query();
        if (Auth::user() && !Auth::user()->canManageVanReservations() && !Auth::user()->hasRole('Coordenador')) {
            $vanQuery->where('user_id', Auth::id());
        }

        $vanStats = [
            'total' => (clone $vanQuery)->count(),
            'pendentes' => (clone $vanQuery)->where('status', 'pendente')->count(),
            'em_andamento' => (clone $vanQuery)->where('status', 'em_andamento')->count(),
            'km_total' => (int) (clone $vanQuery)->where('status', 'concluida')->sum('total_km'),
        ];

        return view('dashboard', [
            'stats' => $stats,
            'resStats' => $resStats,
            'certStats' => $certStats,
            'legalLeaveStats' => $legalLeaveStats,
            'vanStats' => $vanStats,
            'recentTasks' => $recentTasks,
            'recentReservations' => $recentReservations,
            'departments' => Department::all(),
            'users' => User::orderBy('name')->get(),
            'selectedDepartment' => $request->department_id,
            'selectedUser' => $request->user_id,
        ]);
    }

    public function create()
    {
        return view('tasks.create', [
            'departments' => Department::all(),
            'courses' => \App\Models\Course::all(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with(['department', 'course', 'assignee', 'responsible', 'creator'])
            ->orderBy('due_date')
            ->orderBy('created_at', 'desc');

        if ($user && !$user->is_admin && !$user->hasRole('Diretor') && !$user->hasRole('Assessor do Diretor')) {
            $query->where('department_id', $user->department_id ?? -1);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('assigned_to', $request->user_id)
                    ->orWhere('responsible_id', $request->user_id)
                    ->orWhere('created_by', $request->user_id);
            });
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $completedFilter = $request->get('completed_filter', 'ativas');
        if (! in_array($completedFilter, ['ativas', 'ocultas', 'todas'], true)) {
            $completedFilter = 'ativas';
        }

        $columns = [
            'atribuida' => 'Atribuída',
            'em_andamento' => 'Em andamento',
            'em_execucao' => 'Em execução',
            'devolvida' => 'Devolvida',
            'concluida' => 'Concluída',
        ];

        $oneWeekAgo = now()->subDays(7);
        $board = [];

        foreach ($columns as $status => $label) {
            $statusQuery = (clone $query)->where('status', $status);

            if ($status === 'concluida') {
                if ($completedFilter === 'ativas') {
                    $statusQuery->where(function ($q) use ($oneWeekAgo) {
                        $q->where('completed_at', '>=', $oneWeekAgo)
                            ->orWhere(function ($sub) use ($oneWeekAgo) {
                                $sub->whereNull('completed_at')
                                    ->where('updated_at', '>=', $oneWeekAgo);
                            });
                    });
                } elseif ($completedFilter === 'ocultas') {
                    $statusQuery->where(function ($q) use ($oneWeekAgo) {
                        $q->where('completed_at', '<', $oneWeekAgo)
                            ->orWhere(function ($sub) use ($oneWeekAgo) {
                                $sub->whereNull('completed_at')
                                    ->where('updated_at', '<', $oneWeekAgo);
                            });
                    });
                }
            }

            $board[$status] = $statusQuery->get();
        }

        return view('tasks.index', [
            'board' => $board,
            'columns' => $columns,
            'departments' => Department::all(),
            'users' => User::orderBy('name')->get(),
            'selectedDepartment' => $request->department_id,
            'selectedUser' => $request->user_id,
            'selectedPriority' => $request->priority,
            'selectedCompletedFilter' => $completedFilter,
        ]);
    }

    public function show(Task $task)
    {
        $task->load(['department', 'course', 'assignee', 'responsible', 'creator', 'comments.user', 'attachments', 'history.user']);

        return view('tasks.show', compact('task'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'assigned_to' => ['required', 'exists:users,id'],
            'responsible_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:baixa,media,alta'],
            'due_date' => ['nullable', 'date'],
        ]);

        $data['created_by'] = Auth::id();
        $data['status'] = 'atribuida';

        $task = Task::create($data);

        $task->history()->create([
            'user_id' => Auth::id(),
            'from_status' => null,
            'to_status' => 'atribuida',
            'notes' => 'Atividade criada.',
        ]);

        return redirect()->route('tasks.index')->with('success', 'Atividade criada com sucesso!');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
            'comment' => ['nullable', 'string'],
        ]);

        $from = $task->status;

        if ($request->status === 'concluida' && ! $task->canBeCompletedBy(Auth::user())) {
            if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Só o responsável do departamento, diretor ou assessor pode concluir esta atividade.',
                ], 403);
            }
            abort(403, 'Só o responsável do departamento, diretor ou assessor pode concluir esta atividade.');
        }

        $task->status = $request->status;

        if ($request->status === 'concluida') {
            $task->completed_at = now();
            $task->completed_by = Auth::id();
        } else {
            $task->completed_at = null;
            $task->completed_by = null;
        }

        $task->save();

        $task->history()->create([
            'user_id' => Auth::id(),
            'from_status' => $from,
            'to_status' => $request->status,
            'notes' => $request->comment,
        ]);

        if ($request->expectsJson() || $request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status atualizado com sucesso!',
                'task_id' => $task->id,
                'new_status' => $task->status,
            ]);
        }

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function report(Request $request)
    {
        $query = Task::query();

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('assigned_to', $request->user_id)
                    ->orWhere('responsible_id', $request->user_id)
                    ->orWhere('created_by', $request->user_id);
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        return view('tasks.report', [
            'tasks' => $query->with(['department', 'course', 'assignee', 'responsible'])->latest()->get(),
            'departments' => Department::all(),
            'users' => User::orderBy('name')->get(),
            'selectedDepartment' => $request->department_id,
            'selectedUser' => $request->user_id,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ]);
    }
}

