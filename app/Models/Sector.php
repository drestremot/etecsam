<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sector extends Model
{
    use HasFactory;
    protected $guarded = []; // Libera atribuição em massa para teste


   protected $fillable = ['name', 'slug', 'icon', 'summary', 'description', 'images', 'is_active'];

    // ISSO É IMPORTANTE: Converte o JSON do banco para Array no PHP automaticamente
    protected $casts = [
        'images' => 'array',
    ];
}
