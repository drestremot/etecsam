<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'type', 'description', 'content', 'schedule',
        'image', 'photo', 'course_plan', 'unit_id', 'is_active',
    ];

    public function unit() { return $this->belongsTo(Unit::class); }
    public function subjects() { return $this->hasMany(Subject::class); }
    public function laboratories() { return $this->hasMany(Laboratory::class); }

    public function coordinators()
    {
        return $this->belongsToMany(Teacher::class, 'course_coordinators')
                    ->withPivot('role', 'order')
                    ->orderBy('course_coordinators.order');
    }

    public function technicalCoordinators()
    {
        return $this->belongsToMany(Teacher::class, 'course_coordinators')
                    ->withPivot('role', 'order')
                    ->wherePivot('role', 'tecnico')
                    ->orderBy('course_coordinators.order');
    }

    public function decentralizedCoordinators()
    {
        return $this->belongsToMany(Teacher::class, 'course_coordinators')
                    ->withPivot('role', 'order')
                    ->wherePivot('role', 'descentralizado')
                    ->orderBy('course_coordinators.order');
    }
}
