<?php

use App\Http\Controllers\Comment\CommentDestroyController;
use App\Http\Controllers\Comment\CommentIndexController;
use App\Http\Controllers\Comment\CommentStoreController;
use App\Http\Controllers\Comment\CommentUpdateController;
use App\Http\Controllers\Post\PostDestroyController;
use App\Http\Controllers\Post\PostEditController;
use App\Http\Controllers\Post\PostIndexController;
use App\Http\Controllers\Post\PostShowController;
use App\Http\Controllers\Post\PostStoreController;
use App\Http\Controllers\Post\PostUpdateController;
use App\Http\Controllers\User\SignInController;
use App\Http\Controllers\User\SignOutController;
use App\Http\Controllers\User\SignUpController;
use App\Http\Controllers\User\UserDestroyController;
use App\Http\Controllers\User\UserShowController;
use App\Http\Controllers\User\UserUpdateController;
use Illuminate\Support\Facades\Route;

// 사용자
Route::prefix('users')->group(function () {
    Route::prefix('signin')->group(static function () {
        Route::post('/', SignInController::class);
        // Route::post('apple', AppleController::class);
        // Route::post('kakao', KakaoController::class);
    });

    Route::post('signup', SignUpController::class);
    // Route::get('verify', VerifyController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', UserShowController::class);
        Route::post('signout', SignOutController::class);

        // Route::post('update', UserUpdateController::class);
        Route::put('/', UserUpdateController::class);
        // Route::post('delete', UserDestroyController::class);
        Route::delete('/', UserDestroyController::class);
    });
});

// 게시판
Route::prefix('posts')->group(function () {
    Route::get('/', PostIndexController::class);
    Route::get('{post}', PostShowController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', PostStoreController::class);

        Route::prefix('{post}')->group(function () {
            Route::get('edit', PostEditController::class);

            // Route::post('update', PostUpdateController::class);
            Route::put('/', PostUpdateController::class);
            // Route::post('delete', PostDestroyController::class);
            Route::delete('/', PostDestroyController::class);
        });
    });
});

// 댓글
Route::prefix('comments')->group(static function () {
    // Route::get('/', CommentIndexController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', CommentStoreController::class);

        Route::prefix('{comment}')->group(static function () {
            // Route::post('update', CommentUpdateController::class);
            Route::put('/', CommentUpdateController::class);
            // Route::post('delete', CommentDestroyController::class);
            Route::delete('/', CommentDestroyController::class);
        });
    });
});

/*
// 좋아요
Route::group(['prefix' => 'likes', 'middleware' => 'auth:sanctum'], static function () {
    Route::get('/', LikeIndexController::class);
    Route::post('/', LikeStoreController::class);

    // Route::post('delete', LikeDestroyController::class);
    Route::delete('/', LikeDestroyController::class);
});
*/
