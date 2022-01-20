<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 게시글 정보
Route::group(['prefix' => 'posts', 'as' => 'posts.'], function () {
    Route::get('/', [PostController::class, 'index']); // 게시글 리스트
    Route::post('/', [PostController::class, 'store']); // 게시글 작성
    Route::get('{post}', [PostController::class, 'show']); // 게시글 조회
    Route::put('{post}/update', [PostController::class, 'update']); // 게시글 수정
    Route::delete('{post}', [PostController::class, 'destroy']); // 게시글 삭제
});

Route::post('mail', [MailController::class, 'store']);
