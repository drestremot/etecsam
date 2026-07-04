<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationImage extends Model
{
    protected $fillable = ['lab_reservation_id', 'path', 'type'];

    public function reservation()
    {
        return $this->belongsTo(LabReservation::class, 'lab_reservation_id');
    }
}
