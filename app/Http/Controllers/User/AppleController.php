<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\AccessTokenResource;
use App\Models\User;
use App\Models\User\UserApple;
use Carbon\CarbonImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Lcobucci\JWT\Configuration;

class AppleController extends Controller
{
    /**
     * [미사용] 소셜로그인 (애플)
     */
    public function __invoke(Request $request): AccessTokenResource
    {
        $this->getToken($request->redirect_uri);

        DB::beginTransaction();
        try {
            $token = Socialite::driver('apple')->getAccessTokenResponse($request->code);
            $socialUser = Socialite::driver('apple')->userFromToken($token['id_token']);

            $user = User::firstOrCreate([
                'provider' => 'apple',
                'provider_id' => $socialUser->getId(),
            ], [
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
            ]);

            if ($user->wasRecentlyCreated) {
                UserApple::create([
                    'user_id' => $user->id,
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'sub' => $socialUser->getId(),
                    'at_hash' => $socialUser->user['at_hash'] ?? null,
                    'token' => $socialUser->token,
                ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            logger($e);
            abort($e->getCode(), $e->getMessage());
        }

        return new AccessTokenResource(
            $user->createToken('apple')->plainTextToken
        );
    }

    /**
     * Apple JWT Token 설정
     */
    private function getToken($redirectUri): void
    {
        $jwtConfig = app(Configuration::class);

        config()->set('services.apple.redirect', $redirectUri);
        $now = CarbonImmutable::now();
        $token = $jwtConfig->builder()
            ->issuedBy(config('services.apple.team_id'))
            ->issuedAt($now)
            ->expiresAt($now->addHour())
            ->permittedFor('https://appleid.apple.com')
            ->relatedTo(config('services.apple.client_id'))
            ->withHeader('kid', config('services.apple.key_id'))
            ->getToken($jwtConfig->signer(), $jwtConfig->signingKey());

        config()->set('services.apple.client_secret', $token->toString());
    }
}
