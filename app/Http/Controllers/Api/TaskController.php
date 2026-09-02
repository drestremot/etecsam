<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\TaskAttachment;
use App\Models\TaskStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::with(['department', 'course', 'assignee', 'responsible', 'creator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
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

        $tasks = $query->latest()->get()->map(function (Task $task) {
            return $this->serializeTask($task);
        });

        return response()->json([
            'tasks' => $tasks,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'course_id' => ['nullable', 'exists:courses,id'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'responsible_id' => ['required', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['nullable', Rule::in(Task::STATUSES)],
            'priority' => ['nullable', 'in:baixa,media,alta'],
            'due_date' => ['nullable', 'date'],
        ]);

        $data['created_by'] = Auth::id();
        $data['status'] = $data['status'] ?? 'atribuida';

        $task = Task::create($data);

        $this->registerHistory($task, null, $task->status, 'Atividade criada.');

        return response()->json($task->load(['department', 'course', 'assignee', 'responsible']), 201);
    }

    public function show(Task $task)
    {
        $task->load(['department', 'course', 'assignee', 'responsible', 'creator', 'history.user', 'comments.user', 'attachments.user']);

        return response()->json([
            'task' => $this->serializeTask($task, true),
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $task->update($request->only([
            'department_id',
            'course_id',
            'assigned_to',
            'responsible_id',
            'title',
            'description',
            'priority',
            'due_date',
        ]));

        return response()->json($task->refresh());
    }

    public function updateStatus(Request $request, Task $task)
    {
        $request->validate([
            'status' => ['required', Rule::in(Task::STATUSES)],
            'comment' => ['nullable', 'string'],
        ]);

        $fromStatus = $task->status;

        if ($request->status === 'concluida' && !$task->canBeCompletedBy(Auth::user())) {
            return response()->json([
                'success' => false,
                'message' => 'Apenas o responsável, diretor ou coordenador pode concluir esta atividade.',
            ], 403);
        }

        $task->status = $request->status;

        if ($request->status === 'concluida') {
            $task->completed_at = now();
            $task->completed_by = Auth::id();
        }

        $task->save();

        $this->registerHistory($task, $fromStatus, $request->status, $request->comment);

        return response()->json($task->fresh());
    }

    public function addComment(Request $request, Task $task)
    {
        $data = $request->validate([
            'comment' => ['required_without:message', 'string'],
            'message' => ['required_without:comment', 'string'],
        ]);

        $text = $data['comment'] ?? $data['message'];

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $text,
        ]);

        return response()->json($comment->load('user'), 201);
    }

    public function storeAttachment(Request $request, Task $task)
    {
        $request->validate([
            'file' => ['required', 'file'],
        ]);

        $file = $request->file('file');
        $path = $file->store('task-attachments', 'public');

        $attachment = TaskAttachment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'filename' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size_bytes' => $file->getSize(),
        ]);

        return response()->json($attachment, 201);
    }

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

        return response()->json([
            'summary' => [
                'total' => $query->count(),
                'atribuida' => (clone $query)->where('status', 'atribuida')->count(),
                'em_andamento' => (clone $query)->where('status', 'em_andamento')->count(),
                'em_execucao' => (clone $query)->where('status', 'em_execucao')->count(),
                'devolvida' => (clone $query)->where('status', 'devolvida')->count(),
                'concluida' => (clone $query)->where('status', 'concluida')->count(),
            ],
        ]);
    }

    public function reports(Request $request)
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

        return response()->json([
            'items' => $query->with(['department', 'course', 'assignee', 'responsible'])->latest()->get(),
        ]);
    }

    private function registerHistory(Task $task, ?string $fromStatus, string $toStatus, ?string $comment): void
    {
        TaskStatusHistory::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'notes' => $comment,
        ]);
    }

    private function serializeTask(Task $task, bool $full = false): array
    {
        $payload = [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status,
            'priority' => $task->priority,
            'due_date' => $task->due_date?->toDateString(),
            'created_by' => $task->created_by,
            'assigned_to' => $task->assigned_to,
            'responsible_id' => $task->responsible_id,
            'department_id' => $task->department_id,
            'course_id' => $task->course_id,
            'created_at' => $task->created_at?->toISOString(),
            'updated_at' => $task->updated_at?->toISOString(),
        ];

        if ($full) {
            $payload['creator'] = $task->creator ? [
                'id' => $task->creator->id,
                'name' => $task->creator->name,
                'email' => $task->creator->email,
            ] : null;

            $payload['responsible'] = $task->responsible ? [
                'id' => $task->responsible->id,
                'name' => $task->responsible->name,
                'email' => $task->responsible->email,
            ] : null;

            $payload['assignee'] = $task->assignee ? [
                'id' => $task->assignee->id,
                'name' => $task->assignee->name,
                'email' => $task->assignee->email,
            ] : null;

            $payload['history'] = $task->history->map(function ($entry) {
                return [
                    'id' => $entry->id,
                    'from_status' => $entry->from_status,
                    'to_status' => $entry->to_status,
                    'notes' => $entry->notes,
                    'created_at' => $entry->created_at?->toISOString(),
                ];
            })->values()->all();

            $payload['comments'] = $task->comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user' => $comment->user ? [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                    ] : null,
                    'created_at' => $comment->created_at?->toISOString(),
                ];
            })->values()->all();

            $payload['attachments'] = $task->attachments->map(function ($attachment) {
                return [
                    'id' => $attachment->id,
                    'filename' => $attachment->filename,
                    'path' => $attachment->path,
                    'mime_type' => $attachment->mime_type,
                    'created_at' => $attachment->created_at?->toISOString(),
                ];
            })->values()->all();
        }

        return $payload;
    }
}

