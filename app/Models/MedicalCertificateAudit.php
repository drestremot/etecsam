<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalCertificateAudit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'medical_certificate_id',
        'user_id',
        'action',
        'description',
        'changes',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function medicalCertificate(): BelongsTo
    {
        return $this->belongsTo(MedicalCertificate::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'criacao' => 'Criação do Atestado',
            'edicao' => 'Edição dos Dados',
            'substituicao_anexo' => 'Substituição do Anexo',
            'alteracao_status' => 'Alteração de Status',
            'exclusao' => 'Exclusão',
            default => ucfirst($this->action),
        };
    }

    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->action) {
            'criacao' => 'bg-blue-100 text-blue-800',
            'edicao' => 'bg-amber-100 text-amber-800',
            'substituicao_anexo' => 'bg-purple-100 text-purple-800',
            'alteracao_status' => 'bg-emerald-100 text-emerald-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

