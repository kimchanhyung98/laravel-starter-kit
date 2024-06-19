<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\LikeDestroyRequest;
use App\Http\Resources\Like\LikeResource;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\DB;

class LikeDestroyController extends Controller
{
    /**
     * 좋아요 취소
     */
    public function __invoke(LikeDestroyRequest $request): LikeResource
    {
        $model = $this->checkLikeableModel($request);

        try {
            DB::beginTransaction();
            Like::where([
                'user_id' => $request->user()->id,
                'likeable_id' => $request->like_id,
                'likeable_type' => 'App\Models\\'.ucfirst($request->like_type),
            ])->delete();
            $model->decrement('likes_count');
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new LikeResource($model, 204);
    }

    /**
     * 좋아요 취소 가능 확인하고 해당 모델 반환
     */
    private function checkLikeableModel($request)
    {
        $model = match ($request->like_type) {
            'post' => Post::find($request->like_id),
            'comment' => Comment::find($request->like_id),
            default => null,
        };

        if (! $model) {
            abort(404);
        }

        $like = Like::where([
            'user_id' => $request->user()->id,
            'likeable_id' => $request->like_id,
            'likeable_type' => 'App\Models\\'.ucfirst($request->like_type),
        ])->exists();

        if (! $like) {
            abort(409);
        }

        return $model;
    }
}
