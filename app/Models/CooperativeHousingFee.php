<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeHousingFee extends Model
{
    protected $fillable = [
        'month',
        'amount',
    ];

    protected $casts = [
        'month' => 'date',
        'amount' => 'decimal:2',
    ];

    public function payments()
    {
        return $this->hasMany(CooperativeHousingPayment::class);
    }
}
