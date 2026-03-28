<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    public function unit() { return $this->belongsTo(Unit::class); }
public function subjects() { return $this->hasMany(Subject::class); }
public function laboratories() { return $this->hasMany(Laboratory::class); }

// Coordenadores
public function decentralizedCoordinator() { return $this->belongsTo(Teacher::class, 'decentralized_coordinator_id'); }
public function technicalCoordinator() { return $this->belongsTo(Teacher::class, 'technical_coordinator_id'); }
}
