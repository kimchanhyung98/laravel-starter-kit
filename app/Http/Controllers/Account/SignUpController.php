<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\SignUpRequest;
use App\Http\Resources\Account\AccessTokenResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    /**
     * Sign up
     *
     * @return AccessTokenResource
     */
    public function __invoke(SignUpRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = User::firstOrCreate([
                'email' => $request->email,
            ], [
                'name' => $request->name,
                'nickname' => $request->nickname ?? $request->name,
                'password' => Hash::make($request->password),
            ]);

            if (! $user->wasRecentlyCreated) {
                abort(409, 'already exists');
            }
            // $user->sendEmailVerificationNotification();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new AccessTokenResource(
            $user->createToken('token-name')->plainTextToken
        );
    }
}
