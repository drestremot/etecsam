<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReservation extends Model
{
    protected $table = 'lab_reservations';

    protected $fillable = [
        'user_id', 'space_id', 'auxiliar_id', 'coordenador_id',
        'reservation_date', 'start_time', 'end_time',
        'description', 'obs', 'auxiliar_obs', 'coordenador_obs', 'status',
        'checklist_file', 'scanned_doc',
        'delivery_photo', 'return_photo',
        'confirmed_by_auxiliar_at', 'finalized_at',
        'professor_released_at', 'auxiliar_released_at',
        'validated_at', 'professor_signed_at',
    ];

    protected $casts = [
        'reservation_date'         => 'date',
        'confirmed_by_auxiliar_at' => 'datetime',
        'finalized_at'             => 'datetime',
        'professor_released_at'    => 'datetime',
        'auxiliar_released_at'     => 'datetime',
        'validated_at'             => 'datetime',
        'professor_signed_at'      => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function space()
    {
        return $this->belongsTo(Space::class);
    }

    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar_id');
    }

    public function coordenador()
    {
        return $this->belongsTo(User::class, 'coordenador_id');
    }

    public function ambosLiberaram(): bool
    {
        return $this->professor_released_at !== null && $this->auxiliar_released_at !== null;
    }

    public function isVisibleTo(User $user): bool
    {
        if ($user->is_admin) return true;
        if ($this->user_id === $user->id) return true;
        if ($this->coordenador_id === $user->id) return true;
        if ($this->auxiliar_id === $user->id) return true;

        // Fallback para reservas criadas antes do vínculo coordenador/auxiliar existir.
        if ($this->coordenador_id === null && $this->status === 'pre_alocada' && $user->hasRole('Coordenador')) return true;
        if ($this->auxiliar_id === null && in_array($this->status, ['aprovada', 'em_execucao', 'aguardando_conferencia']) && $user->hasRole('Auxiliar')) return true;

        return false;
    }

    public function canBeActedOnByCoordenador(User $user): bool
    {
        if ($user->is_admin) return true;
        if ($this->coordenador_id === null) return $user->hasRole('Coordenador');
        return $this->coordenador_id === $user->id;
    }

    public function canBeFinalizedByAuxiliar(User $user): bool
    {
        if ($user->is_admin) return true;
        if ($this->auxiliar_id === null) return $user->hasRole('Auxiliar');
        return $this->auxiliar_id === $user->id;
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'lab_material_reservation')
            ->withPivot('quantity_requested', 'quantity_used', 'delivered', 'returned', 'delivered_at', 'returned_at')
            ->withTimestamps();
    }

    public function images()
    {
        return $this->hasMany(ReservationImage::class, 'lab_reservation_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pre_alocada'            => 'Aguardando aprovação',
            'aprovada'               => 'Aprovada — preparar lab',
            'em_execucao'            => 'Em execução',
            'aguardando_conferencia' => 'Aguardando conferência',
            'aguardando_validacao'   => 'Aguardando validação do coordenador',
            'validada'               => 'Validada e arquivada',
            'concluida'              => 'Concluída',
            'finalizada'             => 'Finalizada',
            'recusada'               => 'Recusada',
            default                  => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pre_alocada'            => 'gray',
            'aprovada'               => 'blue',
            'em_execucao'            => 'yellow',
            'aguardando_conferencia' => 'orange',
            'conferida'              => 'purple',
            'concluida'              => 'green',
            'finalizada'             => 'green',
            'recusada'               => 'red',
            default                  => 'gray',
        };
    }
}
