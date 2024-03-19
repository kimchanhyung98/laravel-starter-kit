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
    public const string PROVIDER = 'kakao';

    /**
     * SocialLogin (Kakao).
     *
     * @return AccessTokenResource
     */
    public function __invoke(Request $request)
    {
        DB::beginTransaction();

        try {
            // config()->set('services.kakao.redirect', $request->redirect_uri);
            $token = Socialite::driver(self::PROVIDER)->getAccessTokenResponse($request->code)['access_token'];
            $socialUser = Socialite::driver(self::PROVIDER)->userFromToken($token);

            $user = User::firstOrCreate([
                'provider'    => self::PROVIDER,
                'provider_id' => $socialUser->getId(),
            ], [
                'name'  => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
            ]);

            if ($user->wasRecentlyCreated) {
                $account = $socialUser->user['kakao_account'];
                UserKakao::create([
                    'user_id'   => $user->id,
                    'email'     => $socialUser->getEmail(),
                    'name'      => $socialUser->getName(),
                    'nickname'  => $socialUser->getNickname(),
                    'avatar'    => $socialUser->getAvatar(),
                    'gender'    => $account['gender'] ?? null,
                    'birthday'  => $account['birthday'] ?? null,
                    'calendar'  => $account['birthday_type'] ?? null,
                    'age_range' => $account['age_range'] ?? null,
                    'sub'       => $socialUser->getId(),
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new AccessTokenResource(
            $user->createToken(self::PROVIDER)->plainTextToken
        );
    }
}
