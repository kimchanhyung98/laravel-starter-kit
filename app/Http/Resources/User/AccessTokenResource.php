<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessTokenResource extends JsonResource
{
    public function __construct($resource, public int $statusCode = 200)
    {
        parent::__construct($resource);
    }

    public function withResponse($request, $response): void
    {
        $response->setStatusCode($this->statusCode);
    }

    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource,
        ];
    }
}
