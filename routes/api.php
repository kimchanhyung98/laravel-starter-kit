<?php

use App\Http\Controllers\Auth\AppleController;
use App\Http\Controllers\Auth\KakaoController;
use App\Http\Controllers\Auth\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// User
Route::prefix('auth')->group(static function () {
    Route::get('user', UserController::class)->middleware('auth:sanctum');

    Route::post('apple', AppleController::class);
    Route::post('kakao', KakaoController::class);
});

// Board
Route::group(['prefix' => 'posts', 'controller' => 'PostController'], static function () {
    Route::get('/', 'index');
    Route::get('{post}', 'show');

    Route::middleware('auth:sanctum')->group(static function () {
        Route::post('/', 'store');
        Route::prefix('{post}')->group(static function () {
            // Route::get('/', 'show')->withoutMiddleware('auth:sanctum');
            Route::put('/', 'update');
            Route::delete('/', 'destroy');
        });
    });
});
