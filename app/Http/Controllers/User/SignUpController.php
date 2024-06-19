<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SignUpRequest;
use App\Http\Resources\User\AccessTokenResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    /**
     * 회원 가입
     */
    public function __invoke(SignUpRequest $request): AccessTokenResource
    {
        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            // $user->sendEmailVerificationNotification();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), __('user.signup_denied'));
        }

        return new AccessTokenResource(
            $user->createToken('token-name')->plainTextToken, 201
        );
    }
}
