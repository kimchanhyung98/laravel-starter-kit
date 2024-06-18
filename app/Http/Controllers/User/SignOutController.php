<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Common\MessageResource;
use Illuminate\Http\Request;

class SignOutController extends Controller
{
    /**
     * 로그아웃
     */
    public function __invoke(Request $request): MessageResource
    {
        $request->user()->tokens()->delete();
        // $request->user()->currentAccessToken()->delete();

        return new MessageResource([
            'message' => __('user.signout')
        ], 204);
    }
}
