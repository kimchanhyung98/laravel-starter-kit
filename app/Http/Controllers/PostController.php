<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\IndexResource;
use App\Http\Resources\Post\MessageResource;
use App\Http\Resources\Post\ShowResource;
use App\Models\Board\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return IndexResource::collection(
            Post::with('user')->paginate(10)
        );
    }

    public function store(Request $request): MessageResource
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'type' => $request->type ?? null,
            'title' => $request->title,
            'contents' => $request->contents,
            'is_open' => $request->is_open ?? false,
        ]);

        return new MessageResource([
            'id' => $post->id,
            'message' => '게시글이 등록되었습니다.',
        ]);
    }

    public function show(Post $post): ShowResource
    {
        $post->increment('hit');

        return new ShowResource($post);
    }

    public function update(Request $request, Post $post): MessageResource
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '게시글 작성자만 수정할 수 있습니다.');
        }

        $post->update([
            'type' => $request->type ?? null,
            'title' => $request->title,
            'contents' => $request->contents,
            'is_open' => $request->is_open ?? false,
        ]);

        return new MessageResource([
            'id' => $post->id,
            'message' => '게시글이 수정되었습니다.',
        ]);
    }

    public function destroy(Post $post): MessageResource
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '게시글 작성자만 삭제할 수 있습니다.');
        }

        $post->delete();

        return new MessageResource([
            'id' => 0,
            'message' => '게시글이 삭제되었습니다.',
        ]);
    }
}
