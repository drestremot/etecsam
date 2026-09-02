<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Teacher extends Model
{
    use Auditable;

    protected $fillable = [
        'name',
        'role',
        'specialty',
        'bio',
        'email',
        'phone',
        'photo',
        'lattes_url',
        'birth_date',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
