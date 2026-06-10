<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'logo', 'website', 'description', 'active', 'order',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order');
    }
}
