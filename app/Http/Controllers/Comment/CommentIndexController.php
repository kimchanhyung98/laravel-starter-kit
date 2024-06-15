<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentIndexRequest;
use App\Http\Resources\Comment\CommentIndexResource;
use App\Models\Comment;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentIndexController extends Controller
{
    /**
     * [미사용] 게시글 댓글 목록
     */
    public function __invoke(CommentIndexRequest $request): AnonymousResourceCollection
    {
        return CommentIndexResource::collection(
            Comment::where('post_id', $request->post_id)
                ->whereNull('parent_id')
                ->with('user:id,name', 'children.user:id,name')
                ->simplePaginate(10)
        );
    }
}
