<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\Auditable;

class VanReservation extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'vehicle_id',
        'purpose',
        'destination',
        'departure_date',
        'departure_time',
        'return_date',
        'return_time',
        'passengers_count',
        'passenger_list',
        'driver_type',
        'driver_name',
        'driver_cnh',
        'driver_phone',
        'is_within_72h_deadline',
        'hours_in_advance',
        'status',
        'initial_km',
        'final_km',
        'total_km',
        'initial_km_photo',
        'final_km_photo',
        'fuel_level_departure',
        'fuel_level_return',
        'checklist_notes',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'director_notes',
        'completed_by',
        'completed_at',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_within_72h_deadline' => 'boolean',
        'hours_in_advance' => 'integer',
        'passengers_count' => 'integer',
        'initial_km' => 'integer',
        'final_km' => 'integer',
        'total_km' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(VanReservationAudit::class)->orderBy('created_at', 'desc');
    }

    public function recordAudit(string $action, string $notes, array $details = [], ?int $userId = null): VanReservationAudit
    {
        return $this->audits()->create([
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'notes' => $notes,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'Aguardando Liberação da Diretora',
            'aprovada' => 'Liberada / Autorizada',
            'rejeitada' => 'Recusada',
            'em_andamento' => 'Em Viagem / Saída Registrada',
            'concluida' => 'Concluída / KM Final Registrada',
            'cancelada' => 'Cancelada',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'pendente' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'aprovada' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'rejeitada' => 'bg-red-100 text-red-800 border border-red-200',
            'em_andamento' => 'bg-blue-100 text-blue-800 border border-blue-200',
            'concluida' => 'bg-purple-100 text-purple-800 border border-purple-200',
            'cancelada' => 'bg-gray-100 text-gray-800 border border-gray-200',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getDepartureDateTimeAttribute(): Carbon
    {
        $time = $this->departure_time ?? '08:00';
        return Carbon::parse($this->departure_date->format('Y-m-d') . ' ' . $time);
    }

    public function getReturnDateTimeAttribute(): Carbon
    {
        $time = $this->return_time ?? '18:00';
        return Carbon::parse($this->return_date->format('Y-m-d') . ' ' . $time);
    }

    public function calculateTotalKm(): ?int
    {
        if ($this->initial_km !== null && $this->final_km !== null) {
            $diff = max(0, $this->final_km - $this->initial_km);
            $this->total_km = $diff;
            $this->save();
            return $diff;
        }
        return null;
    }
}

