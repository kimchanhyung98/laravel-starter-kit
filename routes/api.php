<?php

use App\Http\Controllers\Account\AppleController;
use App\Http\Controllers\Account\KakaoController;
use App\Http\Controllers\Account\SignInController;
use App\Http\Controllers\Account\SignUpController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\User\UserController;
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

// Account
Route::prefix('accounts')->group(static function () {
    Route::post('signup', SignUpController::class);
    // Route::get('verify', VerifyController::class);

    Route::prefix('signin')->group(static function () {
        Route::post('/', SignInController::class);
        Route::post('apple', AppleController::class);
        Route::post('kakao', KakaoController::class);
    });

    Route::middleware('auth:sanctum')->group(static function () {
        Route::get('/', UserController::class);
        // Route::put('/', UserUpdateController::class);
        // Route::delete('/', UserDeleteController::class);
    });
});

// Board
Route::group(['prefix' => 'posts', 'controller' => PostController::class], static function () {
    Route::get('/', 'index');
    Route::get('{post}', 'show');

    Route::middleware('auth:sanctum')->group(static function () {
        Route::post('/', 'store');
        Route::prefix('{post}')->group(static function () {
            Route::get('edit', 'edit');
            Route::put('/', 'update');
            Route::delete('/', 'destroy');
        });
    });
});
