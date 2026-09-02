<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Subject extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'course_id',
        'teacher_id',
        'name',
        'workload',
        'ptd_file',
        'semester',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
