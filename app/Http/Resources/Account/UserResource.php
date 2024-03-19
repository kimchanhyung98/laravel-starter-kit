<?php

namespace App\Http\Resources\Account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'nickname' => $this->nickname,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'provider' => $this->provider,
        ];
    }
}
