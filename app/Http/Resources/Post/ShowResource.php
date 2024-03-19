<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                // 'id' => $this->user->id,
                'name' => $this->user->name,
                'nickname' => $this->user->nickname,
            ],
            'type' => $this->type,
            'title' => $this->title,
            'contents' => $this->contents,
            'hit' => $this->hit,
        ];
    }
}
