<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\MessageResource;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Gate;

class PostDestroyController extends Controller
{
    /**
     * 게시글 삭제
     */
    public function __invoke(Post $post): MessageResource
    {
        Gate::authorize('delete', $post);

        try {
            // if ($post->like_count > 100) abort(403, '좋아요');
            $post->delete();
            $post->unsearchable();
        } catch (Exception $e) {
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'message' => __('post.destroy'),
        ]);
    }
}
