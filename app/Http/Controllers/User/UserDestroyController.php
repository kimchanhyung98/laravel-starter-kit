<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDestroyRequest;
use App\Http\Resources\Common\MessageResource;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserDestroyController extends Controller
{
    /**
     * 회원 탈퇴
     */
    public function __invoke(UserDestroyRequest $request): MessageResource
    {
        $user = $request->user();
        // if (! Hash::check($request->password, $user->password)) abort(401, __('user.unauthorized'));
        try {
            DB::beginTransaction();

            // todo : optional sociallogin
            $this->revoke(user: $user);

            // todo : if deleted_reason needed, change to post method
            // $user->update(['deleted_reason' => $request->deleted_reason]);

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

    /**
     * [미사용] 소셜 로그인 탈퇴 처리
     */
    private function revoke($user): void
    {
        if ($user->provider === 'apple') {
            $user->apple->delete();
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
        }
    }
}
