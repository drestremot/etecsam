<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\Auditable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Auditable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'registration_number',
        'role',
        'department_id',
        'course_id',
        'phone',
        'profile_photo',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'is_admin'             => 'boolean',
            'is_active'            => 'boolean',
            'must_change_password' => 'boolean',
        ];
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'email', 'email');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class, 'department_user')->withTimestamps();
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user')->withTimestamps();
    }

    public function labReservations(): HasMany
    {
        return $this->hasMany(LabReservation::class, 'user_id');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function responsibleTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'responsible_id');
    }

    public function medicalCertificates(): HasMany
    {
        return $this->hasMany(MedicalCertificate::class);
    }

    public function legalLeaves(): HasMany
    {
        return $this->hasMany(LegalLeave::class);
    }

    public function legalLeaveRequests(): HasMany
    {
        return $this->hasMany(LegalLeaveRequest::class);
    }

    public function vanReservations(): HasMany
    {
        return $this->hasMany(VanReservation::class);
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function workSchedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function timeClockRecords(): HasMany
    {
        return $this->hasMany(TimeClockRecord::class);
    }

    public function scopeCoordenadores($query)
    {
        return $query->role('Coordenador')->orWhere('is_admin', true);
    }

    public function auxiliaresVinculados()
    {
        return $this->belongsToMany(User::class, 'auxiliar_coordenador', 'coordenador_id', 'auxiliar_id')->orderBy('name');
    }

    public function coordenadoresVinculados()
    {
        return $this->belongsToMany(User::class, 'auxiliar_coordenador', 'auxiliar_id', 'coordenador_id')->orderBy('name');
    }

    public function auxiliaresParaAprovacao()
    {
        if ($this->is_admin) {
            return User::role('Auxiliar')->where('is_active', true)->orderBy('name')->get();
        }
        return $this->auxiliaresVinculados()->where('is_active', true)->get();
    }

    public function canManageMedicalCertificates(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $allowedRoles = [
            'Superintendente',
            'Diretor',
            'Diretor da Unidade',
            'Diretora de Serviços',
            'Funcionário da Diretoria de Serviços',
            'Assessor do Diretor',
            'Responsável do Departamento',
            'Coordenador',
        ];

        if ($this->hasAnyRole($allowedRoles)) {
            return true;
        }

        if ($this->role && in_array($this->role, $allowedRoles)) {
            return true;
        }

        if ($this->department && $this->department->slug === 'diretoria-de-servicos') {
            return true;
        }

        return false;
    }

    public function canManageVanReservations(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $allowedRoles = [
            'Superintendente',
            'Diretor',
            'Diretor da Unidade',
            'Diretora de Serviços',
            'Funcionário da Diretoria de Serviços',
            'Assessor do Diretor',
        ];

        if ($this->hasAnyRole($allowedRoles)) {
            return true;
        }

        if ($this->role && in_array($this->role, $allowedRoles)) {
            return true;
        }

        if ($this->department && $this->department->slug === 'diretoria-de-servicos') {
            return true;
        }

        return false;
    }

    public function canViewVanAudit(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $auditRoles = [
            'Superintendente',
            'Diretor',
            'Diretor da Unidade',
            'Diretora de Serviços',
        ];

        if ($this->hasAnyRole($auditRoles)) {
            return true;
        }

        if ($this->role && in_array($this->role, $auditRoles)) {
            return true;
        }

        if ($this->department && $this->department->slug === 'diretoria-de-servicos' && ($this->hasRole('Responsável do Departamento') || str_contains(strtolower($this->role ?? ''), 'diretor') || str_contains(strtolower($this->role ?? ''), 'superintendente'))) {
            return true;
        }

        return false;
    }

    public function canViewMedicalAudit(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $auditRoles = [
            'Superintendente',
            'Diretor',
            'Diretor da Unidade',
            'Diretora de Serviços',
        ];

        if ($this->hasAnyRole($auditRoles)) {
            return true;
        }

        if ($this->role && in_array($this->role, $auditRoles)) {
            return true;
        }

        if ($this->department && $this->department->slug === 'diretoria-de-servicos' && ($this->hasRole('Responsável do Departamento') || str_contains(strtolower($this->role ?? ''), 'diretor') || str_contains(strtolower($this->role ?? ''), 'superintendente'))) {
            return true;
        }

        return false;
    }

    public function canViewSystemAudit(): bool
    {
        if ($this->is_admin) {
            return true;
        }

        $auditRoles = [
            'Superintendente',
            'Diretor',
            'Diretor da Unidade',
            'Diretora de Serviços',
            'Diretor de Serviços',
        ];

        if ($this->hasAnyRole($auditRoles)) {
            return true;
        }

        if ($this->role && in_array($this->role, $auditRoles)) {
            return true;
        }

        if ($this->department && $this->department->slug === 'diretoria-de-servicos' && ($this->hasRole('Responsável do Departamento') || str_contains(strtolower($this->role ?? ''), 'diretor') || str_contains(strtolower($this->role ?? ''), 'superintendente'))) {
            return true;
        }

        return false;
    }

    public function getScheduleRoleTypeAttribute(): string
    {
        $roleStr = strtolower($this->role ?? '');
        $hasTeacherRole = $this->roles->contains(function ($r) {
            $n = strtolower($r->name);
            return str_contains($n, 'profess') || str_contains($n, 'docente') || str_contains($n, 'teacher');
        });
        if ($hasTeacherRole || str_contains($roleStr, 'profess') || str_contains($roleStr, 'docente') || str_contains($roleStr, 'teacher')) {
            return 'teacher';
        }

        $hasCoordRole = $this->roles->contains(function ($r) {
            $n = strtolower($r->name);
            return str_contains($n, 'coordenad');
        });
        if ($hasCoordRole || str_contains($roleStr, 'coordenad')) {
            return 'coordinator';
        }

        return 'staff';
    }

    public function getTeacherColorAttribute(): array
    {
        return WorkSchedule::getTeacherColorForUser($this->id, $this->name);
    }
}
