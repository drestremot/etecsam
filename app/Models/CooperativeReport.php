<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeReport extends Model
{
    protected $fillable = [
        'title',
        'category',
        'period',
        'file_path',
        'url',
        'published_at',
    ];

    protected $casts = ['published_at' => 'date'];
}
