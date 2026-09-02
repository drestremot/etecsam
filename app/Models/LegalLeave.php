<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class LegalLeave extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'type',
        'description',
        'document_number',
        'event_date',
        'days_granted',
        'days_used',
        'days_remaining',
        'expiration_date',
        'attachment_path',
        'notes',
        'created_by',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'expiration_date' => 'date',
            'days_granted' => 'integer',
            'days_used' => 'integer',
            'days_remaining' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(LegalLeaveRequest::class)->orderBy('requested_date', 'desc');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(LegalLeaveAudit::class)->orderBy('created_at', 'desc');
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'eleicao' => 'Serviço Eleitoral (TRE)',
            'juri_popular' => 'Tribunal do Júri',
            'doacao_sangue' => 'Doação de Sangue',
            'alistamento' => 'Alistamento Eleitoral',
            'casamento' => 'Casamento (Gala)',
            'luto' => 'Luto (Nojo)',
            'convocacao_judicial' => 'Convocação Judicial',
            default => 'Outro Previsto em Lei',
        };
    }

    public function getLegalBasisAttribute(): string
    {
        return match ($this->type) {
            'eleicao' => 'Art. 98 da Lei 9.504/1997 (Folga em Dobro)',
            'juri_popular' => 'Art. 430 do Código de Processo Penal',
            'doacao_sangue' => 'Art. 473, IV da CLT / Lei Estadual SP (1 dia/ano)',
            'alistamento' => 'Art. 473, V da CLT (Até 2 dias)',
            'casamento' => 'Art. 473, II da CLT / Estatuto dos Servidores',
            'luto' => 'Art. 473, I da CLT / Estatuto dos Servidores',
            'convocacao_judicial' => 'Art. 822 da CLT / Convocação Oficial',
            default => 'Legislação Trabalhista / Estatutária',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'ativo' => 'bg-emerald-100 text-emerald-800',
            'esgotado' => 'bg-gray-100 text-gray-800',
            'expirado' => 'bg-red-100 text-red-800',
            default => 'bg-blue-100 text-blue-800',
        };
    }

    public function recalculateBalance(): void
    {
        $used = $this->requests()->where('status', 'aprovado')->sum('requested_days');
        $this->days_used = (int) $used;
        $this->days_remaining = max(0, $this->days_granted - $this->days_used);
        $this->status = $this->days_remaining === 0 ? 'esgotado' : 'ativo';
        $this->save();
    }

    public function recordAudit(string $action, string $description, ?array $changes = null, ?int $userId = null): LegalLeaveAudit
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

