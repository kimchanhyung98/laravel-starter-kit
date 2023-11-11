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
Route::prefix('login')->group(static function () {
    Route::post('apple', AppleController::class);
    Route::post('kakao', KakaoController::class);
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'user'], static function () {
    Route::get('/', UserController::class);
});

// Board
Route::group(['prefix' => 'posts'], static function () {
    // @todo
});
