<?php

namespace App\Http\Resources\Common;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            $this->mergeWhen(isset($this->resource['id']), [
                'id' => $this->resource['id'] ?? null,
            ]),
            'message' => $this->resource['message'],
        ];
    }
}
