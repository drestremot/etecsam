<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VanReservationAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'van_reservation_id',
        'user_id',
        'action',
        'notes',
        'details',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    public function vanReservation(): BelongsTo
    {
        return $this->belongsTo(VanReservation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'solicitacao' => 'Solicitação de Reserva',
            'liberacao_aprovacao' => 'Liberação & Autorização pela Diretora',
            'rejeicao' => 'Recusa de Solicitação',
            'saida_km' => 'Registro de Saída (KM Inicial)',
            'retorno_km' => 'Registro de Retorno (KM Final & Conclusão)',
            'edicao' => 'Edição de Dados',
            'cancelamento' => 'Cancelamento',
            default => ucfirst(str_replace('_', ' ', $this->action)),
        };
    }
}

