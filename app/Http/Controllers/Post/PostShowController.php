<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostShowResource;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Gate;

class PostShowController extends Controller
{
    /**
     * 게시글 조회
     */
    public function __invoke(Post $post): PostShowResource
    {
        Gate::authorize('view', $post);

        try {
            $post->increment('hit');
        } catch (Exception $e) {
            logger($e->getMessage());
        }

        // return new PostShowResource($post);
        return new PostShowResource(
            $post->load('comments')
        );
    }
}
