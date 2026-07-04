<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReservation extends Model
{
    protected $table = 'lab_reservations';

    protected $fillable = [
        'user_id', 'space_id', 'auxiliar_id',
        'reservation_date', 'start_time', 'end_time',
        'description', 'obs', 'status',
        'checklist_file', 'scanned_doc',
        'delivery_photo', 'return_photo',
        'confirmed_by_auxiliar_at', 'finalized_at',
    ];

    protected $casts = [
        'reservation_date'        => 'date',
        'confirmed_by_auxiliar_at' => 'datetime',
        'finalized_at'            => 'datetime',
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
            'pre_alocada'            => 'Pré-alocada',
            'aprovada'               => 'Aprovada',
            'em_execucao'            => 'Em execução',
            'aguardando_conferencia' => 'Aguardando conferência',
            'conferida'              => 'Conferida',
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
