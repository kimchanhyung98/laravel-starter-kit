<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    /**
     * 게시글 리스트
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'posts' => Post::where('is_open', true)->paginate(10),
        ]);
    }

    /**
     * 게시글 작성
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'type' => ['required', Rule::in(['notice', 'faq']),],
            'name' => ['required', 'string', 'max:100'],
            'content' => ['required'],
        ]);

        Post::create($validatedData);

        return response()->json([
            'message' => '작성되었습니다.',
        ]);
    }

    /**
     * 게시글 조회
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'post' => $post,
        ]);
    }

    /**
     * 게시글 수정
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        $validatedData = $request->validate([
            'type' => ['required', Rule::in(['notice', 'faq']),],
            'name' => ['required', 'string', 'max:100'],
            'content' => ['required'],
        ]);

        $post->update([
            'type' => $validatedData['type'],
            'name' => $validatedData['name'],
            'content' => $validatedData['content'],
        ]);

        return response()->json([
            'message' => '수정되었습니다.',
        ]);
    }

    /**
     * 게시글 삭제
     *
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => '삭제되었습니다.',
        ]);
    }
}
