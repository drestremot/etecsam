<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class MedicalCertificate extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'type',
        'doctor_name',
        'crm',
        'cid',
        'start_date',
        'end_date',
        'days',
        'description',
        'attachment_path',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'reviewed_at' => 'datetime',
            'days' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(MedicalCertificateAudit::class)->orderBy('created_at', 'desc');
    }

    public function recordAudit(string $action, string $description, ?array $changes = null, ?int $userId = null): MedicalCertificateAudit
    {
        return $this->audits()->create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'medico' => 'Médico',
            'odontologico' => 'Odontológico',
            'acompanhamento' => 'Acompanhamento',
            'declaracao_horas' => 'Declaração de Horas',
            default => 'Outro',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'homologado' => 'Homologado',
            'rejeitado' => 'Rejeitado',
            default => 'Pendente',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'homologado' => '#27ae60',
            'rejeitado' => '#eb5757',
            default => '#f2994a',
        };
    }

    public function isPdf(): bool
    {
        $ext = strtolower(pathinfo($this->attachment_path, PATHINFO_EXTENSION));
        return $ext === 'pdf';
    }

    public function isImage(): bool
    {
        $ext = strtolower(pathinfo($this->attachment_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}

