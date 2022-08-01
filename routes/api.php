<?php

use App\Http\Controllers\{
    MailController,
    PostController,
    UserController,
};
use App\Http\Controllers\Auth\{
    LoginController,
    RegisterController,
    SocialLoginController,
};
use Illuminate\Support\Facades\Route;


// Sanctum
Route::group(['prefix' => 'auth'], static function () {
    Route::get('user', [UserController::class, 'auth'])->middleware('auth:sanctum');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', RegisterController::class);
});

// Socialite
Route::group(['prefix' => 'login', 'as' => 'login.'], static function () {
    Route::get('{provider}', [SocialLoginController::class, 'redirectToProvider']);
    Route::get('{provider}/callback', [SocialLoginController::class, 'handleProviderCallback']);
});

// Posts
Route::group(['prefix' => 'posts', 'controller' => PostController::class], static function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{post}', 'show');
    Route::put('{post}/update', 'update');
    Route::delete('{post}', 'destroy');
});


// Mail
Route::post('mail', [MailController::class, 'store']);
