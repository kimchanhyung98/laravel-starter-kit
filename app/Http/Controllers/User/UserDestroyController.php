<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Resources\MessageResource;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserDestroyController extends Controller
{
    /**
     * 회원 탈퇴
     */
    public function __invoke(UserDestroyRequest $request): MessageResource
    {
        $user = Auth::user();
        if (! $user) {
            abort(401, __('user.unauthorized'));
        }

        try {
            DB::beginTransaction();
            $this->revoke($user, $request->deleted_reason);
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

    /**
     * 소셜 로그인 탈퇴 처리
     */
    private function revoke($user, $reason): void
    {
        if ($user->provider === 'apple') {
            $user->apple->delete();
            // @todo : Apple 로그인 탈퇴 처리
        } elseif ($user->provider === 'kakao') {
            $user->kakao->delete();
            /*
            Http::withHeaders(['Authorization' => 'KakaoAK '.config('services.kakao.admin_key')])
                ->asForm()->throw()
                ->post('https://kapi.kakao.com/v1/user/unlink', [
                    'target_id_type' => 'user_id',
                    'target_id' => $user->provider_id,
                ]);
            */
        } else {
            $user->update(['password' => null]);
        }

        $user->update(['deleted_reason' => $reason]);
        $user->tokens()->delete();
        $user->delete();
    }
}
