<?php

namespace App\Http\Resources\Post;

use App\Http\Resources\Comment\CommentIndexResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PostShowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'type' => $this->type,
            'title' => $this->title,
            'contents' => $this->contents,
            'hit' => $this->hit,
            'like_count' => $this->likes_count,
            'comment_count' => $this->comments->count(),
            $this->mergeWhen($this->comments->count(), [
                'comments' => CommentIndexResource::collection($this->comments),
            ]),
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'is_liked' => $this->likes->contains('user_id', Auth::id()),
            'is_editable' => $this->user_id === Auth::id(),
        ];
    }
}
