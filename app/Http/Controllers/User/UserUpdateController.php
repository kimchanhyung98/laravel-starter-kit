<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserUpdateController extends Controller
{
    /**
     * 회원 정보 수정
     */
    public function __invoke(UserUpdateRequest $request): UserResource
    {
        $user = $request->user();
        try {
            DB::beginTransaction();
            $user->update([
                'nickname' => $request->nickname,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), __('user.update_denied'));
        }

        return new UserResource($user);
    }
}
