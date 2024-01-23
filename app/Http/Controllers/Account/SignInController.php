<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignInRequest;
use App\Http\Resources\Auth\AccessTokenResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;

class SignInController extends Controller
{
    public function __invoke(SignInRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                abort(401, 'invalid credentials');
            }

            $user = User::where('email', $request->email)->firstOrFail();
        } catch (Exception $e) {
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new AccessTokenResource(
            $user->createToken('token-name')->plainTextToken
        );
    }
}
