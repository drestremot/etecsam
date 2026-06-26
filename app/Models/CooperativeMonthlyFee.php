<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeMonthlyFee extends Model
{
    protected $fillable = [
        'month',
        'amount',
    ];

    protected $casts = [
        'month' => 'date',
        'amount' => 'decimal:2',
    ];

    public function dues()
    {
        return $this->hasMany(CooperativeDue::class);
    }
}
