<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResource extends JsonResource
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
            'confirmPassword' => $this->password_confirmation,
        ]);
    }
}
