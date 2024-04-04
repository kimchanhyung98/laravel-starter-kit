<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserUpdateController extends Controller
{
    /**
     * 회원 정보 수정
     */
    public function __invoke(Request $request): MessageResource
    {
        $user = Auth::user();
        if (! $user) {
            abort(401, __('user.unauthorized'));
        }

        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                // 'password' => $request->password ? Hash::make($request->password) : $user->password,
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), __('user.update_denied'));
        }

        return new MessageResource([
            'message' => __('user.update'),
        ]);
    }
}
