<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $guarded = [];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Novo relacionamento: Coordenador da Unidade
    public function coordinator()
    {
        return $this->belongsTo(Teacher::class, 'coordinator_id');
    }
}
