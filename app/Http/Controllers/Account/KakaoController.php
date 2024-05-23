<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Resources\Account\AccessTokenResource;
use App\Models\User;
use App\Models\User\UserKakao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class KakaoController extends Controller
{
    /**
     * 소셜로그인 (카카오)
     */
    public function __invoke(Request $request): AccessTokenResource
    {
        DB::beginTransaction();
        try {
            // config()->set('services.kakao.redirect', $request->redirect_uri);
            $token = Socialite::driver('kakao')->getAccessTokenResponse($request->code)['access_token'];
            $socialUser = Socialite::driver('kakao')->userFromToken($token);

            $user = User::firstOrCreate([
                'provider' => 'kakao',
                'provider_id' => $socialUser->getId(),
            ], [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
            ]);

            if ($user->wasRecentlyCreated) {
                $account = $socialUser->user['kakao_account'];
                UserKakao::create([
                    'user_id' => $user->id,
                    'email' => $socialUser->getEmail(),
                    'name' => $socialUser->getName(),
                    'nickname' => $socialUser->getNickname(),
                    'avatar' => $socialUser->getAvatar(),
                    'gender' => $account['gender'] ?? null,
                    'birthday' => $account['birthday'] ?? null,
                    'calendar' => $account['birthday_type'] ?? null,
                    'age_range' => $account['age_range'] ?? null,
                    'sub' => $socialUser->getId(),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new AccessTokenResource(
            $user->createToken('kakao')->plainTextToken
        );
    }
}
