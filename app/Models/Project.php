<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'objectives', 'status',
        'start_date', 'end_date', 'image', 'responsible_id', 'department_id', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function responsible()
    {
        return $this->belongsTo(Teacher::class, 'responsible_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
