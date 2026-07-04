<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'name',
        'description',
        'stock_quantity',
        'unit',
        'patrimony_number',
        'photo',
    ];

    public function labReservations()
    {
        return $this->belongsToMany(LabReservation::class, 'lab_material_reservation')
            ->withPivot('quantity_requested', 'quantity_used', 'delivered', 'returned', 'delivered_at', 'returned_at')
            ->withTimestamps();
    }
}
