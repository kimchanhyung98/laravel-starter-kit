<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Resources\Common\MessageResource;
use App\Models\Post;
use Exception;

class PostStoreController extends Controller
{
    /**
     * 게시글 저장
     */
    public function __invoke(PostStoreRequest $request): MessageResource
    {
        $this->checkSpamming($request->user()->id);
        // todo : optional check

        try {
            $post = Post::create([
                'user_id' => $request->user()->id,
                'type' => $request->type ?? 'free',
                'title' => $request->title,
                'contents' => $request->contents,
                'is_published' => $request->is_published ?? true,
            ]);
            $post->searchable();
        } catch (Exception $e) {
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'id' => $post->id,
            'message' => __('post.store'),
        ], 201);
    }

    /**
     * 중복 게시글 확인
     */
    private function checkSpamming(int $userId): void
    {
        $latestCount = Post::where('user_id', $userId)
            ->where('created_at', '>', now()->subSeconds(30))
            ->count();

        if ($latestCount > 5) {
            abort(429, __('post.store_denied'));
        }
    }
}
