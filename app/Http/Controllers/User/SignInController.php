<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\SignInRequest;
use App\Http\Resources\User\AccessTokenResource;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            // Gate::allowIf(Hash::check($request->password, $user->password), '로그인 실패');
            if (! Hash::check($request->password, $user->password)) {
                abort(404);
            }
        } catch (ModelNotFoundException $e) {
            // logger($e);
            abort(401, __('user.signin_denied'));
        } catch (Exception $e) {
            logger($e);
            abort(500);
        }

        return new AccessTokenResource(
            $user->createToken('api')->plainTextToken
        );
    }
}
