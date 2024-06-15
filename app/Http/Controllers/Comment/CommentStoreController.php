<?php

namespace App\Http\Controllers\Comment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Http\Resources\Common\MessageResource;
use App\Models\Comment;
use Exception;

class CommentStoreController extends Controller
{
    /**
     * 댓글 작성
     */
    public function __invoke(CommentStoreRequest $request): MessageResource
    {
        /*
        $this->checkSpamming($request->user()->id);
        if ($request->parent_id) {
            $this->checkReplyDepth($request->parent_id);
        }
        */

        try {
            $comment = Comment::create([
                'user_id' => $request->user()->id,
                'post_id' => $request->post_id,
                'parent_id' => $request->parent_id,
                'contents' => $request->contents,
            ]);
        } catch (Exception $e) {
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new MessageResource([
            'id' => $comment->id,
            'message' => '댓글_작성',
        ], 201);
    }

    /**
     * 중복 댓글 확인
     */
    private function checkSpamming(int $userId): void
    {
        $latestCount = Comment::where('user_id', $userId)
            ->where('created_at', '>', now()->subSeconds(30))
            ->count();

        if ($latestCount > 5) {
            abort(429, '작성_실패');
        }
    }

    /**
     * 댓글 depth (제한) 확인
     */
    private function checkReplyDepth(int $parentId): void
    {
        if (Comment::find($parentId)->parent_id) {
            abort(403, '작성_실패');
        }
    }
}
