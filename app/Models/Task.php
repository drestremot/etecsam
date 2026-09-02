<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class Task extends Model
{
    use HasFactory, Auditable;

    public const STATUSES = [
        'atribuida',
        'em_andamento',
        'em_execucao',
        'devolvida',
        'concluida',
    ];

    protected $fillable = [
        'department_id',
        'course_id',
        'created_by',
        'assigned_to',
        'responsible_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'completed_at',
        'completed_by',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_id');
    }

    public function finisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function history(): HasMany
    {
        return $this->hasMany(TaskStatusHistory::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class)->latest();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(TaskNotification::class)->latest();
    }

    public function canBeCompletedBy(User $user): bool
    {
        if ($user->id === $this->responsible_id) {
            return true;
        }

        if ($user->hasRole(['Diretor', 'Assessor do Diretor', 'Responsável do Departamento']) || $user->is_admin) {
            return true;
        }

        return false;
    }
}

