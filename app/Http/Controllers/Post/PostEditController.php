<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Resources\Post\PostEditResource;
use App\Models\Post;
use Illuminate\Support\Facades\Gate;

class PostEditController extends Controller
{
    /**
     * [미사용] 게시글 수정 페이지
     */
    public function __invoke(Post $post): PostEditResource
    {
        Gate::authorize('update', $post);

        return new PostEditResource($post);
    }
}
