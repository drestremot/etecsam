<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeMember extends Model
{
    protected $fillable = [
        'name',
        'registration_number',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
