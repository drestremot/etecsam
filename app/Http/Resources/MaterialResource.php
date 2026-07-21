<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'description'      => $this->description,
            'stock_quantity'   => $this->stock_quantity,
            'unit'             => $this->unit,
            'patrimony_number' => $this->patrimony_number,
            'photo_url'        => photo_url($this->photo),
            'pivot'            => $this->when($this->pivot, fn () => [
                'quantity_requested' => $this->pivot->quantity_requested,
                'quantity_used'      => $this->pivot->quantity_used,
                'delivered'          => (bool) $this->pivot->delivered,
                'returned'           => (bool) $this->pivot->returned,
                'delivered_at'       => $this->pivot->delivered_at,
                'returned_at'        => $this->pivot->returned_at,
            ]),
        ];
    }
}
