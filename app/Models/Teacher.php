<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
   protected $fillable = [
    'name',
    'role',
    'specialty',
    'email',
    'phone',
    'photo',
    'lattes_url', // <--- ADICIONE ESTA LINHA
];
}
