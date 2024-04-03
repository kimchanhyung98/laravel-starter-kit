<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDestroyController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();

        try {
            DB::beginTransaction();
            $user->update(['deleted_reason' => $request->deleted_reason]);
            $user->tokens()->delete();
            $user->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), __('user.destroy_denied'));
        }

        return new MessageResource([
            'message' => __('user.destroy'),
        ]);
    }
}
