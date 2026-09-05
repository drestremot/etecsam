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
        'course_id',
        'subject_id',
        'course_name',
        'shift_name',
        'subject_name',
        'class_name',
        'division',
        'classroom',
        'schedule_type',
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
            'course_id'         => 'integer',
            'subject_id'        => 'integer',
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

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
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

    public static function getDayColorConfig(): array
    {
        return [
            1 => [
                'name'        => 'Segunda-feira',
                'short'       => 'SEG',
                'badge_class' => 'bg-blue-50 text-blue-700 border-blue-300/80 shadow-2xs',
                'bg_solid'    => 'bg-blue-600',
                'text_color'  => 'text-blue-700',
                'hex'         => '#1d4ed8',
                'border_hex'  => '#60a5fa',
                'light_bg'    => '#eff6ff',
            ],
            2 => [
                'name'        => 'Terça-feira',
                'short'       => 'TER',
                'badge_class' => 'bg-emerald-50 text-emerald-700 border-emerald-300/80 shadow-2xs',
                'bg_solid'    => 'bg-emerald-600',
                'text_color'  => 'text-emerald-700',
                'hex'         => '#059669',
                'border_hex'  => '#34d399',
                'light_bg'    => '#ecfdf5',
            ],
            3 => [
                'name'        => 'Quarta-feira',
                'short'       => 'QUA',
                'badge_class' => 'bg-orange-50 text-orange-800 border-orange-300/80 shadow-2xs',
                'bg_solid'    => 'bg-orange-600',
                'text_color'  => 'text-orange-800',
                'hex'         => '#ea580c',
                'border_hex'  => '#fb923c',
                'light_bg'    => '#fff7ed',
            ],
            4 => [
                'name'        => 'Quinta-feira',
                'short'       => 'QUI',
                'badge_class' => 'bg-purple-50 text-purple-700 border-purple-300/80 shadow-2xs',
                'bg_solid'    => 'bg-purple-600',
                'text_color'  => 'text-purple-700',
                'hex'         => '#7c3aed',
                'border_hex'  => '#a78bfa',
                'light_bg'    => '#faf5ff',
            ],
            5 => [
                'name'        => 'Sexta-feira',
                'short'       => 'SEX',
                'badge_class' => 'bg-pink-50 text-pink-700 border-pink-300/80 shadow-2xs',
                'bg_solid'    => 'bg-pink-600',
                'text_color'  => 'text-pink-700',
                'hex'         => '#db2777',
                'border_hex'  => '#f472b6',
                'light_bg'    => '#fdf2f8',
            ],
            6 => [
                'name'        => 'Sábado',
                'short'       => 'SÁB',
                'badge_class' => 'bg-indigo-50 text-indigo-700 border-indigo-300/80 shadow-2xs',
                'bg_solid'    => 'bg-indigo-600',
                'text_color'  => 'text-indigo-700',
                'hex'         => '#4f46e5',
                'border_hex'  => '#818cf8',
                'light_bg'    => '#eef2ff',
            ],
            0 => [
                'name'        => 'Domingo',
                'short'       => 'DOM',
                'badge_class' => 'bg-slate-100 text-slate-700 border-slate-300 shadow-2xs',
                'bg_solid'    => 'bg-slate-600',
                'text_color'  => 'text-slate-700',
                'hex'         => '#475569',
                'border_hex'  => '#94a3b8',
                'light_bg'    => '#f8fafc',
            ],
        ];
    }

    public static function getTeacherColorPalette(): array
    {
        return [
            ['name' => 'Azul Safira', 'bg' => '#eff6ff', 'border' => '#bfdbfe', 'text' => '#1d4ed8', 'badge' => 'bg-blue-50 text-blue-700 border-blue-200', 'chip' => 'bg-blue-600 text-white', 'dot' => '#2563eb', 'card' => 'border-l-4 border-l-blue-500 bg-blue-50/40'],
            ['name' => 'Verde Esmeralda', 'bg' => '#ecfdf5', 'border' => '#a7f3d0', 'text' => '#047857', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'chip' => 'bg-emerald-600 text-white', 'dot' => '#059669', 'card' => 'border-l-4 border-l-emerald-500 bg-emerald-50/40'],
            ['name' => 'Roxo Violeta', 'bg' => '#faf5ff', 'border' => '#e9d5ff', 'text' => '#7e22ce', 'badge' => 'bg-purple-50 text-purple-700 border-purple-200', 'chip' => 'bg-purple-600 text-white', 'dot' => '#9333ea', 'card' => 'border-l-4 border-l-purple-500 bg-purple-50/40'],
            ['name' => 'Laranja Âmbar', 'bg' => '#fffbeb', 'border' => '#fde68a', 'text' => '#b45309', 'badge' => 'bg-amber-50 text-amber-800 border-amber-200', 'chip' => 'bg-amber-600 text-white', 'dot' => '#d97706', 'card' => 'border-l-4 border-l-amber-500 bg-amber-50/40'],
            ['name' => 'Rosa Magenta', 'bg' => '#fdf2f8', 'border' => '#fbcfe8', 'text' => '#be185d', 'badge' => 'bg-pink-50 text-pink-700 border-pink-200', 'chip' => 'bg-pink-600 text-white', 'dot' => '#db2777', 'card' => 'border-l-4 border-l-pink-500 bg-pink-50/40'],
            ['name' => 'Teal Turquesa', 'bg' => '#f0fdfa', 'border' => '#99f6e4', 'text' => '#0f766e', 'badge' => 'bg-teal-50 text-teal-700 border-teal-200', 'chip' => 'bg-teal-600 text-white', 'dot' => '#0d9488', 'card' => 'border-l-4 border-l-teal-500 bg-teal-50/40'],
            ['name' => 'Indigo Real', 'bg' => '#eef2ff', 'border' => '#c7d2fe', 'text' => '#4338ca', 'badge' => 'bg-indigo-50 text-indigo-700 border-indigo-200', 'chip' => 'bg-indigo-600 text-white', 'dot' => '#4f46e5', 'card' => 'border-l-4 border-l-indigo-500 bg-indigo-50/40'],
            ['name' => 'Ciano Oceano', 'bg' => '#ecfeff', 'border' => '#a5f3fc', 'text' => '#0e7490', 'badge' => 'bg-cyan-50 text-cyan-700 border-cyan-200', 'chip' => 'bg-cyan-600 text-white', 'dot' => '#0891b2', 'card' => 'border-l-4 border-l-cyan-500 bg-cyan-50/40'],
            ['name' => 'Vermelho Coral', 'bg' => '#fef2f2', 'border' => '#fecaca', 'text' => '#b91c1c', 'badge' => 'bg-red-50 text-red-700 border-red-200', 'chip' => 'bg-red-600 text-white', 'dot' => '#dc2626', 'card' => 'border-l-4 border-l-red-500 bg-red-50/40'],
            ['name' => 'Lime Folha', 'bg' => '#f7fee7', 'border' => '#d9f99d', 'text' => '#4d7c0f', 'badge' => 'bg-lime-50 text-lime-800 border-lime-200', 'chip' => 'bg-lime-600 text-white', 'dot' => '#65a30d', 'card' => 'border-l-4 border-l-lime-500 bg-lime-50/40'],
            ['name' => 'Fúcsia Orquídea', 'bg' => '#fdf4ff', 'border' => '#f5d0fe', 'text' => '#a21caf', 'badge' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-200', 'chip' => 'bg-fuchsia-600 text-white', 'dot' => '#c026d3', 'card' => 'border-l-4 border-l-fuchsia-500 bg-fuchsia-50/40'],
            ['name' => 'Laranja Tangerina', 'bg' => '#fff7ed', 'border' => '#ffedd5', 'text' => '#c2410c', 'badge' => 'bg-orange-50 text-orange-700 border-orange-200', 'chip' => 'bg-orange-600 text-white', 'dot' => '#ea580c', 'card' => 'border-l-4 border-l-orange-500 bg-orange-50/40'],
            ['name' => 'Sky Celeste', 'bg' => '#f0f9ff', 'border' => '#bae6fd', 'text' => '#0369a1', 'badge' => 'bg-sky-50 text-sky-700 border-sky-200', 'chip' => 'bg-sky-600 text-white', 'dot' => '#0284c7', 'card' => 'border-l-4 border-l-sky-500 bg-sky-50/40'],
            ['name' => 'Rose Sakura', 'bg' => '#fff1f2', 'border' => '#fecdd3', 'text' => '#be123c', 'badge' => 'bg-rose-50 text-rose-700 border-rose-200', 'chip' => 'bg-rose-600 text-white', 'dot' => '#e11d48', 'card' => 'border-l-4 border-l-rose-500 bg-rose-50/40'],
            ['name' => 'Violeta Profundo', 'bg' => '#f5f3ff', 'border' => '#ddd6fe', 'text' => '#6d28d9', 'badge' => 'bg-violet-50 text-violet-700 border-violet-200', 'chip' => 'bg-violet-600 text-white', 'dot' => '#7c3aed', 'card' => 'border-l-4 border-l-violet-500 bg-violet-50/40'],
            ['name' => 'Verde Menta', 'bg' => '#f0fdf4', 'border' => '#bbf7d0', 'text' => '#15803d', 'badge' => 'bg-green-50 text-green-700 border-green-200', 'chip' => 'bg-green-600 text-white', 'dot' => '#16a34a', 'card' => 'border-l-4 border-l-green-500 bg-green-50/40'],
        ];
    }

    public static function getTeacherColorForUser(int|string|null $userId, ?string $userName = ''): array
    {
        $palette = self::getTeacherColorPalette();
        $key = $userId ? (int) $userId : crc32($userName ?? 'teacher');
        $index = abs($key) % count($palette);
        return $palette[$index];
    }

    public function getDayNameAttribute(): string
    {
        return self::getDaysList()[$this->day_of_week] ?? 'Dia indefinido';
    }

    public function getDayShortAttribute(): string
    {
        return self::getDayColorConfig()[$this->day_of_week]['short'] ?? 'DOM';
    }

    public function getDayBadgeClassAttribute(): string
    {
        return self::getDayColorConfig()[$this->day_of_week]['badge_class'] ?? 'bg-gray-100 text-gray-700 border-gray-200';
    }

    public function getDayColorHexAttribute(): string
    {
        return self::getDayColorConfig()[$this->day_of_week]['hex'] ?? '#475569';
    }

    public function getTeacherColorAttribute(): array
    {
        return self::getTeacherColorForUser($this->user_id, $this->user?->name);
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

    public function isClassSchedule(): bool
    {
        return ($this->schedule_type ?? 'class') === 'class';
    }

    public function isCoordinationSchedule(): bool
    {
        return ($this->schedule_type ?? '') === 'coordination';
    }

    public function isAdministrativeSchedule(): bool
    {
        return ($this->schedule_type ?? '') === 'administrative';
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


