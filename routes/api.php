<?php

use App\Http\Controllers\{
    MailController,
    PostController,
    UserController,
};
use App\Http\Controllers\Auth\{
    LoginController,
    SocialLoginController,
};
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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// Sanctum
Route::group(['prefix' => 'auth'], static function () {
    Route::get('user', [UserController::class, 'auth'])->middleware('auth:sanctum');
    Route::post('login', [LoginController::class, 'login']);
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
