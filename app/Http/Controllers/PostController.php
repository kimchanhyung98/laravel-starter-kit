<?php

namespace App\Http\Controllers;

use App\Http\Resources\Post\EditResource;
use App\Http\Resources\Post\IndexResource;
use App\Http\Resources\Post\MessageResource;
use App\Http\Resources\Post\ShowResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        return IndexResource::collection(
            Post::search($request->q)
                // ->when($request->type, fn ($query, $type) => $query->where('type', $type))
                // ->query(fn (Builder $query) => $query->with('user:id,nickname'))
                ->when($request->type, function ($query, $type) {
                    return $query->where('type', $type);
                })->query(function ($query) {
                    return $query->with('user:id,nickname');
                })->paginate(10)
        );
    }

    public function store(Request $request): MessageResource
    {
        try {
            DB::beginTransaction();
            $post = Post::create([
                'user_id' => Auth::id(),
                'type' => $request->type ?? null,
                'title' => $request->title,
                'contents' => $request->contents,
                'is_open' => $request->is_open ?? false,
            ]);
            DB::commit();

            $post->searchable();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'id' => $post->id,
            'message' => __('post.store'),
        ]);
    }

    public function show(Post $post): ShowResource
    {
        try {
            $post->increment('hit');
        } catch (Exception $e) {
            logger($e->getMessage());
        }

        return new ShowResource($post);
    }

    public function edit(Post $post): EditResource
    {
        Gate::authorize('update', $post);

        return new EditResource($post);
    }

    public function update(Request $request, Post $post): MessageResource
    {
        Gate::authorize('update', $post);

        try {
            DB::beginTransaction();
            $post->update([
                'type' => $request->type,
                'title' => $request->title,
                'contents' => $request->contents,
                'is_open' => $request->is_open,
            ]);
            DB::commit();

            $post->searchable();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'id' => $post->id,
            'message' => __('post.update'),
        ]);
    }

    public function destroy(Post $post): MessageResource
    {
        Gate::authorize('delete', $post);

        try {
            $post->delete();

            $post->unsearchable();
        } catch (Exception $e) {
            logger($e->getMessage());
            abort(500);
        }

        return new MessageResource([
            'id' => 0,
            'message' => __('post.destroy'),
        ]);
    }
}
