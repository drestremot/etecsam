<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'module',
        'auditable_type',
        'auditable_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActionBadgeClass(): string
    {
        return match (strtolower($this->action)) {
            'login'          => 'bg-blue-50 text-blue-700 border-blue-200',
            'logout'         => 'bg-gray-100 text-gray-700 border-gray-200',
            'created'        => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'updated'        => 'bg-amber-50 text-amber-700 border-amber-200',
            'deleted'        => 'bg-rose-50 text-rose-700 border-rose-200',
            'approved'       => 'bg-teal-50 text-teal-700 border-teal-200',
            'rejected'       => 'bg-red-50 text-red-700 border-red-200',
            'password_change'=> 'bg-purple-50 text-purple-700 border-purple-200',
            'sync'           => 'bg-indigo-50 text-indigo-700 border-indigo-200',
            default          => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    public function getActionLabel(): string
    {
        return match (strtolower($this->action)) {
            'login'          => '🔑 Acesso ao Sistema',
            'logout'         => '🚪 Encerramento de Sessão',
            'created'        => '✨ Criação de Registro',
            'updated'        => '✏️ Alteração / Edição',
            'deleted'        => '🗑️ Exclusão de Registro',
            'approved'       => '✅ Aprovação / Liberação',
            'rejected'       => '❌ Recusa / Rejeição',
            'password_change'=> '🔒 Troca de Senha',
            'sync'           => '⚡ Sincronização',
            default          => ucfirst($this->action),
        };
    }

    public function getModuleIcon(): string
    {
        return match ($this->module) {
            'Autenticação'        => '🔐',
            'Demandas (KanbanTec)'=> '📋',
            'Reservas de Lab'     => '🔬',
            'Atestados Médicos'   => '🏥',
            'Folgas Legais'       => '⚖️',
            'Van Escolar'         => '🚐',
            'Cursos & Grade'      => '🎓',
            'Usuários & Perfis'   => '👥',
            'Espaços Didáticos'   => '🏢',
            'Inventário'          => '📦',
            default               => '📁',
        };
    }
}

