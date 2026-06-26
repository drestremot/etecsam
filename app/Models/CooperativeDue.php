<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeDue extends Model
{
    protected $fillable = [
        'cooperative_member_id',
        'cooperative_monthly_fee_id',
        'paid',
        'paid_at',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'date',
    ];

    public function member()
    {
        return $this->belongsTo(CooperativeMember::class, 'cooperative_member_id');
    }

    public function monthlyFee()
    {
        return $this->belongsTo(CooperativeMonthlyFee::class, 'cooperative_monthly_fee_id');
    }
}
