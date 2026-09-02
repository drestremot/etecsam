<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Space extends Model
{
    use Auditable;

    protected $fillable = ['name', 'description', 'auxiliar_id', 'laboratory_id'];

    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar_id');
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function labReservations()
    {
        return $this->hasMany(LabReservation::class);
    }
}
