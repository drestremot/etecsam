<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeHousingPayment extends Model
{
    protected $fillable = [
        'cooperative_housing_tenant_id',
        'cooperative_housing_fee_id',
        'paid',
        'paid_at',
    ];

    protected $casts = [
        'paid' => 'boolean',
        'paid_at' => 'date',
    ];

    public function tenant()
    {
        return $this->belongsTo(CooperativeHousingTenant::class, 'cooperative_housing_tenant_id');
    }

    public function fee()
    {
        return $this->belongsTo(CooperativeHousingFee::class, 'cooperative_housing_fee_id');
    }
}
