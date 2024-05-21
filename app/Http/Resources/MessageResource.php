<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
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
