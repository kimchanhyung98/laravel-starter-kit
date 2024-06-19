<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommentIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'parent_id' => $this->parent_id,
            'contents' => $this->contents,
            'likes_count' => $this->likes_count,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'is_liked' => $this->likes->contains('user_id', Auth::id()),
            'is_editable' => $this->user_id === Auth::id(),
            // 'children' => self::collection($this->whenLoaded('children')),
            $this->mergeWhen($this->children->count(), [
                'replies' => self::collection($this->children),
            ]),
        ];
    }
}
