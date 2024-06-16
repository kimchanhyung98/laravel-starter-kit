<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\MessageResource;
use App\Models\Comment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentDestroyController extends Controller
{
    /**
     * 댓글 삭제
     */
    public function __invoke(Request $request, Comment $comment): MessageResource
    {
        Gate::authorize('delete', $comment);

        try {
            $comment->delete();
        } catch (Exception $e) {
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new MessageResource([
            'message' => '댓글_삭제',
        ]);
    }
}
