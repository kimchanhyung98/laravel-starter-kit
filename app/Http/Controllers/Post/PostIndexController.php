<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostIndexRequest;
use App\Http\Resources\Post\PostIndexResource;
use App\Models\Post;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostIndexController extends Controller
{
    /**
     * 게시글 리스트
     */
    public function __invoke(PostIndexRequest $request): AnonymousResourceCollection
    {
        return PostIndexResource::collection(
            Post::search($request->q)
                ->when($request->type, function ($query, $type) {
                    return $query->where('type', $type);
                })->query(function ($query) {
                    return $query->published()->with('user:id,nickname');
                })->orderBy('id', 'desc')
                ->simplePaginate($request->per_page ?? 10)
        );
    }
}
