<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentUpdateRequest;
use App\Http\Resources\Common\MessageResource;
use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\Gate;

class CommentUpdateController extends Controller
{
    /**
     * 댓글 수정
     */
    public function __invoke(CommentUpdateRequest $request, Comment $comment): MessageResource
    {
        Gate::authorize('update', $comment);

        try {
            $comment->update(['contents' => $request->contents]);
        } catch (Exception $e) {
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new MessageResource([
            'id' => $comment->id,
            'message' => '댓글_수정',
        ]);
    }
}
