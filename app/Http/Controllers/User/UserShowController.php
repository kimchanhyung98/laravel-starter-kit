<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class UserShowController extends Controller
{
    /**
     * 사용자 정보 조회
     */
    public function __invoke(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
