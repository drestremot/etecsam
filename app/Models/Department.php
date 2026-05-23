<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'email', 'phone', 'location',
        'responsible_id', 'is_active',
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

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
