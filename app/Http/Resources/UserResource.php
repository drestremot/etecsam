<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'email'                => $this->email,
            'registration_number'  => $this->registration_number,
            'is_admin'             => $this->is_admin,
            'roles'                => $this->getRoleNames(),
            'photo_url'            => photo_url($this->teacher?->photo),
        ];
    }
}
