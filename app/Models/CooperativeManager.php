<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeManager extends Model
{
    protected $fillable = [
        'name',
        'role',
        'photo',
        'email',
        'phone',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
