<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeHousingTenant extends Model
{
    protected $fillable = [
        'name',
        'room',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function payments()
    {
        return $this->hasMany(CooperativeHousingPayment::class);
    }

    /**
     * Em dia = tem um pagamento confirmado para toda mensalidade ja cadastrada.
     */
    public function isUpToDate(): bool
    {
        $totalFees = CooperativeHousingFee::count();
        if ($totalFees === 0) {
            return true;
        }

        return $this->payments()->where('paid', true)->count() >= $totalFees;
    }
}
