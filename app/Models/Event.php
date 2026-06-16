<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'start_date', 'end_date', 'location', 'color', 'image', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function photos()
    {
        return $this->hasMany(EventPhoto::class)->orderBy('order');
    }
}
