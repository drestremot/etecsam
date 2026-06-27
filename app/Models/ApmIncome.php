<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApmIncome extends Model
{
    protected $fillable = ['description', 'category', 'amount', 'due_date', 'received_date', 'notes'];
    protected $casts = ['amount' => 'decimal:2', 'due_date' => 'date', 'received_date' => 'date'];

    public function status(): string
    {
        if ($this->received_date) {
            return 'Recebido';
        }
        return $this->due_date->isPast() ? 'Atrasado' : 'Pendente';
    }
}
