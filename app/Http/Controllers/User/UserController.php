<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __invoke()
    {
        return new UserResource(Auth::user());
    }
}
