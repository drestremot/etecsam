<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LabReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'status'            => $this->status,
            'status_label'      => $this->status_label,
            'status_color'      => $this->status_color,
            'reservation_date'  => $this->reservation_date?->format('Y-m-d'),
            'start_time'        => $this->start_time,
            'end_time'          => $this->end_time,
            'description'       => $this->description,
            'obs'               => $this->obs,
            'auxiliar_obs'      => $this->auxiliar_obs,
            'coordenador_obs'   => $this->coordenador_obs,
            'checklist_file'    => photo_url($this->checklist_file),
            'scanned_doc'       => photo_url($this->scanned_doc),
            'confirmed_by_auxiliar_at' => $this->confirmed_by_auxiliar_at,
            'finalized_at'              => $this->finalized_at,
            'professor_released_at'     => $this->professor_released_at,
            'auxiliar_released_at'      => $this->auxiliar_released_at,
            'validated_at'               => $this->validated_at,
            'professor_signed_at'        => $this->professor_signed_at,
            'user'          => new UserResource($this->whenLoaded('user')),
            'space'         => new SpaceResource($this->whenLoaded('space')),
            'auxiliar'      => new UserResource($this->whenLoaded('auxiliar')),
            'coordenador'   => new UserResource($this->whenLoaded('coordenador')),
            'materials'     => MaterialResource::collection($this->whenLoaded('materials')),
            'images'        => ReservationImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
