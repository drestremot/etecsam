<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeExpense extends Model
{
    protected $fillable = [
        'description',
        'category',
        'amount',
        'due_date',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    public function status(): string
    {
        if ($this->paid_date) {
            return 'Pago';
        }

        return $this->due_date->isPast() ? 'Atrasado' : 'Pendente';
    }
}
