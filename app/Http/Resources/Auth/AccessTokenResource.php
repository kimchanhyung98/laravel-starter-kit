<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Resources\Json\JsonResource;

class AccessTokenResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'access_token' => $this->resource,
        ];
    }
}
