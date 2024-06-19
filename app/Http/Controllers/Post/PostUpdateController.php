<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Common\MessageResource;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\Gate;

class PostUpdateController extends Controller
{
    /**
     * 게시글 수정
     */
    public function __invoke(PostUpdateRequest $request, Post $post): MessageResource
    {
        Gate::authorize('update', $post);

        try {
            $post->update([
                // 'type' => $request->type,
                'title' => $request->title,
                'contents' => $request->contents,
                // 'is_published' => $request->is_published,
            ]);
            $post->searchable();
        } catch (Exception $e) {
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'id' => $post->id,
            'message' => __('post.update'),
        ]);
    }
}
