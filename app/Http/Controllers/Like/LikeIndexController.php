<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeIndexRequest;
use App\Models\Comment;
use App\Models\Like;

class LikeIndexController extends Controller
{
    /**
     * [미사용] 댓글의 좋아요 목록 (ids)
     */
    public function __invoke(LikeIndexRequest $request): array
    {
        return Like::where('likeable_type', Comment::class)
            ->whereIn('likeable_id', Comment::where('post_id', $request->post_id)->pluck('id'))
            ->pluck('id');
    }
}
