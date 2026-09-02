<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSchedule extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'unit_id',
        'day_of_week',
        'shift_name',
        'start_time',
        'end_time',
        'break_start_time',
        'break_end_time',
        'tolerance_minutes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'day_of_week'       => 'integer',
            'tolerance_minutes' => 'integer',
            'is_active'         => 'boolean',
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

    public function timeClockRecords(): HasMany
    {
        return $this->hasMany(TimeClockRecord::class);
    }

    public static function getDaysList(): array
    {
        return [
            1 => 'Segunda-feira',
            2 => 'Terça-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sábado',
            0 => 'Domingo',
        ];
    }

    public function getDayNameAttribute(): string
    {
        return self::getDaysList()[$this->day_of_week] ?? 'Dia indefinido';
    }

    public function getDayShortAttribute(): string
    {
        return match ($this->day_of_week) {
            1 => 'SEG',
            2 => 'TER',
            3 => 'QUA',
            4 => 'QUI',
            5 => 'SEX',
            6 => 'SÁB',
            default => 'DOM',
        };
    }

    public function getFormattedStartTimeAttribute(): string
    {
        return substr($this->start_time, 0, 5);
    }

    public function getFormattedEndTimeAttribute(): string
    {
        return substr($this->end_time, 0, 5);
    }

    public function getFormattedScheduleAttribute(): string
    {
        return "{$this->formatted_start_time} às {$this->formatted_end_time}";
    }

    public function getPlannedDurationMinutes(): int
    {
        $start = Carbon::createFromTimeString($this->start_time);
        $end = Carbon::createFromTimeString($this->end_time);

        $totalMinutes = $end->diffInMinutes($start);

        if ($this->break_start_time && $this->break_end_time) {
            $bStart = Carbon::createFromTimeString($this->break_start_time);
            $bEnd = Carbon::createFromTimeString($this->break_end_time);
            $breakMinutes = $bEnd->diffInMinutes($bStart);
            $totalMinutes -= $breakMinutes;
        }

        return max(0, $totalMinutes);
    }
}

