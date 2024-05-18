<?php

use App\Http\Controllers\ErrorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('errors/{code}', ErrorController::class);
