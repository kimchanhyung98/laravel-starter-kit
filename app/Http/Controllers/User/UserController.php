<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\UserResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * 회원 정보 확인
     */
    public function __invoke(): UserResource
    {
        return new UserResource(Auth::user());
    }
}
