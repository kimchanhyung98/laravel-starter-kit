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
Route::group(['prefix' => 'login'], static function () {
    Route::post('apple', AppleController::class);
    Route::post('kakao', KakaoController::class);
});

Route::group(['middleware' => 'auth:sanctum'], static function () {
    // Route::get('user', UserController::class);
});

// Board
Route::group(['prefix' => 'posts'], static function () {
    // @todo
});
