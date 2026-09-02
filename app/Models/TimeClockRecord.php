<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class TimeClockRecord extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'unit_id',
        'work_schedule_id',
        'recorded_at',
        'record_type',
        'verification_method',
        'photo_snapshot',
        'latitude',
        'longitude',
        'accuracy_meters',
        'distance_to_unit_meters',
        'is_within_geofence',
        'is_within_schedule',
        'delay_minutes',
        'status',
        'justification',
        'justified_by',
        'justified_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'recorded_at'             => 'datetime',
            'justified_at'            => 'datetime',
            'is_within_geofence'      => 'boolean',
            'is_within_schedule'      => 'boolean',
            'delay_minutes'           => 'integer',
            'distance_to_unit_meters' => 'integer',
            'latitude'                => 'float',
            'longitude'               => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function workSchedule(): BelongsTo
    {
        return $this->belongsTo(WorkSchedule::class);
    }

    public function justifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'justified_by');
    }

    public function getPhotoUrlAttribute(): ?string
    {
        if (!$this->photo_snapshot) {
            return null;
        }

        if (str_starts_with($this->photo_snapshot, 'http')) {
            return $this->photo_snapshot;
        }

        return Storage::url($this->photo_snapshot);
    }

    public function getRecordTypeLabel(): string
    {
        return match ($this->record_type) {
            'entry_1'     => '🟢 1ª Entrada',
            'exit_1'      => '🟡 1ª Saída (Intervalo)',
            'entry_2'     => '🟢 2ª Entrada (Retorno)',
            'exit_2'      => '🔴 2ª Saída (Término)',
            'extra_entry' => '🟣 Entrada Extra',
            'extra_exit'  => '⚫ Saída Extra',
            default       => ucfirst($this->record_type),
        };
    }

    public function getRecordTypeBadgeClass(): string
    {
        return match ($this->record_type) {
            'entry_1', 'entry_2' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'exit_1'             => 'bg-amber-50 text-amber-700 border-amber-200',
            'exit_2'             => 'bg-rose-50 text-rose-700 border-rose-200',
            'extra_entry', 'extra_exit' => 'bg-purple-50 text-purple-700 border-purple-200',
            default              => 'bg-gray-50 text-gray-700 border-gray-200',
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'approved'             => '✓ Regular / Validado',
            'flagged_outside_unit' => '⚠️ Fora da Unidade (GPS)',
            'flagged_late'         => '⏰ Atraso Registrado',
            'flagged_extra'        => '⌛ Horário Não Previsto',
            'justified'            => '📋 Justificado pela Direção',
            default                => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'approved'             => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'flagged_outside_unit' => 'bg-rose-100 text-rose-800 border-rose-200',
            'flagged_late'         => 'bg-amber-100 text-amber-800 border-amber-200',
            'flagged_extra'        => 'bg-purple-100 text-purple-800 border-purple-200',
            'justified'            => 'bg-blue-100 text-blue-800 border-blue-200',
            default                => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}

