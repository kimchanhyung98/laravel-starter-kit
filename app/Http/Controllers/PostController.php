<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\PostIndexResource;
use App\Http\Resources\Post\PostMessageResource;
use App\Http\Resources\Post\PostShowResource;
use App\Models\Board\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): PostIndexResource
    {
        return new PostIndexResource(
            Post::with('user')->paginate(10)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): PostMessageResource
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'type' => $request->type ?? null,
            'title' => $request->title,
            'contents' => $request->contents,
            'is_open' => $request->is_open ?? false,
        ]);

        return new PostMessageResource([
            'id' => $post->id,
            'message' => '게시글이 등록되었습니다.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): PostShowResource
    {
        $post->increment('hit');

        return new PostShowResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): PostMessageResource
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

        return new PostMessageResource([
            'id' => $post->id,
            'message' => '게시글이 수정되었습니다.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): PostMessageResource
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '게시글 작성자만 삭제할 수 있습니다.');
        }

        $post->delete();

        return new PostMessageResource([
            'id' => 0,
            'message' => '게시글이 삭제되었습니다.',
        ]);
    }
}
