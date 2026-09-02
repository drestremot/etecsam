<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalLeaveAudit extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'legal_leave_id',
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

    public function legalLeave(): BelongsTo
    {
        return $this->belongsTo(LegalLeave::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'concessao' => 'Concessão de Saldo de Folga',
            'edicao' => 'Edição de Saldo / Dados',
            'substituicao_anexo' => 'Substituição de Comprovante',
            'solicitacao_usufruto' => 'Solicitação de Usufruto de Folga',
            'aprovacao_usufruto' => 'Ciência / Aprovação de Folga',
            'rejeicao_usufruto' => 'Rejeição de Solicitação',
            'cancelamento' => 'Cancelamento de Solicitação',
            default => ucfirst($this->action),
        };
    }

    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->action) {
            'concessao' => 'bg-blue-100 text-blue-800',
            'edicao' => 'bg-amber-100 text-amber-800',
            'solicitacao_usufruto' => 'bg-purple-100 text-purple-800',
            'aprovacao_usufruto' => 'bg-emerald-100 text-emerald-800',
            'rejeicao_usufruto' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

