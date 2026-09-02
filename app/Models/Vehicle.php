<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plate',
        'brand',
        'model',
        'year',
        'capacity',
        'current_km',
        'status',
        'fuel_type',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'current_km' => 'integer',
        'capacity' => 'integer',
        'year' => 'integer',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(VanReservation::class);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->status === 'disponivel';
    }
}

