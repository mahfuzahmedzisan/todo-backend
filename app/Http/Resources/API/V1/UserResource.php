<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'initials' => $this->initials(),
            'is_verified' => $this->is_verified,
            'last_synced_at' => $this->last_synced_at_formatted,
            'created_at' => $this->created_at_formatted,
            'updated_at' => $this->updated_at_formatted,
        ];
    }
}
