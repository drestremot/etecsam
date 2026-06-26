<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CooperativeSale extends Model
{
    protected $fillable = [
        'description',
        'category',
        'amount',
        'sale_date',
        'due_date',
        'received_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'sale_date' => 'date',
        'due_date' => 'date',
        'received_date' => 'date',
    ];

    public function status(): string
    {
        if ($this->received_date) {
            return 'Recebido';
        }

        return $this->due_date->isPast() ? 'Atrasado' : 'Pendente';
    }
}
