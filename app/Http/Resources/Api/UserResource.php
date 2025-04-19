<?php

namespace App\Http\Resources\Api;

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
        return ([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'confirmedPassword' => $this->password_confirmation,
            'rememberToken' => $this->remember_token,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ]);
    }
}
