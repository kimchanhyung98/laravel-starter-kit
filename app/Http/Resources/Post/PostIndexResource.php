<?php

namespace App\Http\Resources\Post;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user ? $this->user->name : '탈퇴한 회원',
            'title' => $this->title,
            'like_count' => $this->likes_count,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
