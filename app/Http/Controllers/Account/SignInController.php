<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignInRequest;
use App\Http\Resources\Account\AccessTokenResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class SignInController extends Controller
{
    /**
     * 로그인
     */
    public function __invoke(SignInRequest $request): AccessTokenResource
    {
        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if (! Hash::check($request->password, $user->password)) {
                abort(401);
            }
        } catch (Exception $e) {
            logger($e);
            abort(401, __('user.signin_denied'));
        }

        return new AccessTokenResource(
            $user->createToken('api')->plainTextToken
        );
    }
}
