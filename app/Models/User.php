<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'registration_number',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'is_active'         => 'boolean',
        ];
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class, 'email', 'email');
    }

    public function labReservations()
    {
        return $this->hasMany(LabReservation::class, 'user_id');
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function scopeCoordenadores($query)
    {
        return $query->role('Coordenador')->orWhere('is_admin', true);
    }

    public function auxiliaresVinculados()
    {
        return $this->belongsToMany(User::class, 'auxiliar_coordenador', 'coordenador_id', 'auxiliar_id')->orderBy('name');
    }

    public function coordenadoresVinculados()
    {
        return $this->belongsToMany(User::class, 'auxiliar_coordenador', 'auxiliar_id', 'coordenador_id')->orderBy('name');
    }

    public function auxiliaresParaAprovacao()
    {
        if ($this->is_admin) {
            return User::role('Auxiliar')->where('is_active', true)->orderBy('name')->get();
        }
        return $this->auxiliaresVinculados()->where('is_active', true)->get();
    }
}
