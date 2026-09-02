<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\Auditable;

class LegalLeaveRequest extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'legal_leave_id',
        'user_id',
        'requested_date',
        'requested_days',
        'reason',
        'is_within_72h_deadline',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'reviewed_at' => 'datetime',
        'is_within_72h_deadline' => 'boolean',
        'requested_days' => 'integer',
    ];

    public function legalLeave(): BelongsTo
    {
        return $this->belongsTo(LegalLeave::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'aprovado' => 'Aprovada / Ciência Tomada',
            'rejeitado' => 'Rejeitada',
            'cancelado' => 'Cancelada',
            default => 'Pendente de Ciência',
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match ($this->status) {
            'aprovado' => 'bg-emerald-100 text-emerald-800',
            'rejeitado' => 'bg-red-100 text-red-800',
            'cancelado' => 'bg-gray-100 text-gray-800',
            default => 'bg-amber-100 text-amber-800',
        };
    }

    public function getHoursInAdvanceAttribute(): int
    {
        $requested = Carbon::parse($this->requested_date)->startOfDay();
        $created = $this->created_at ?? now();
        return (int) $created->diffInHours($requested, false);
    }
}

