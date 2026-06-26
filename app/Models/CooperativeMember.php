<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeMember extends Model
{
    protected $fillable = [
        'name',
        'registration_number',
        'phone',
        'email',
        'sex',
        'guardian_name',
        'guardian_phone',
        'photo',
        'joined_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'date',
    ];

    public function dues()
    {
        return $this->hasMany(CooperativeDue::class);
    }

    /**
     * Em dia = tem um pagamento confirmado para toda mensalidade ja cadastrada.
     */
    public function isUpToDate(): bool
    {
        $totalFees = CooperativeMonthlyFee::count();
        if ($totalFees === 0) {
            return true;
        }

        return $this->dues()->where('paid', true)->count() >= $totalFees;
    }
}
