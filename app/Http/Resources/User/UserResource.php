<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'nickname' => $this->nickname,
            'email' => $this->email,
            // 'provider' => $this->provider,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
