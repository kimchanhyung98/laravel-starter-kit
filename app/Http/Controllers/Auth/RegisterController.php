<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  UserRegisterRequest  $request
     * @return JsonResponse
     */
    public function __invoke(UserRegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => '회원가입이 완료 되었습니다.',
            'token_type' => 'Bearer',
            'access_token' => $token,
        ]);
    }
}
