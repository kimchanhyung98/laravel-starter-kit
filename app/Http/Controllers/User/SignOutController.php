<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SignOutController extends Controller
{
    /**
     * 로그아웃
     */
    public function __invoke(Request $request): Response
    {
        // $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
