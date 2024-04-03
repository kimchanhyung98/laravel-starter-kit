<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserUpdateController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            $user->update([
                'name' => $request->name,
                'nickname' => $request->nickname,
                'email' => $request->email,
                // 'password' => $request->password ? bcrypt($request->password) : $user->password,
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
