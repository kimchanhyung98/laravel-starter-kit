<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the specified authentication page.
     *
     * @param  string  $provider
     * @return RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse
    {
        if (!array_key_exists($provider, config('services'))) {
            return redirect('/');
        }

        // The stateless method may be used to disable session state verification
        return Socialite::driver($provider)->stateless()->redirect();
    }


    /**
     * Obtain the user information from Social service.
     *
     * @param  string  $provider
     * @return RedirectResponse
     * @throws Exception
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        // $user = Socialite::driver($provider)->user();
        // The stateless method may be used to disable session state verification
        $social = Socialite::driver($provider)->stateless()->user();

        if ($user = User::where('email', $social->getEmail())->first()) {
            return $this->login($provider, $user);
        }

        return $this->register($provider, $social);
    }


    /**
     * Register the user.
     *
     * @param  string  $provider
     * @param $social
     * @return RedirectResponse
     */
    private function register(string $provider, $social): RedirectResponse
    {
        $user = User::create([
            'name' => $social->getName(),
            'email' => $social->getEmail(),
            'username' => $social->getNickname(),
            'provider' => $provider,
            'provider_id' => $social->getId(),
            'refresh_token' => $social->refreshToken,
        ]);

        return $this->login($provider, $user);
    }


    /**
     * Login the user then return the token.
     *
     * @param  string  $provider
     * @param  User  $user
     * @return RedirectResponse
     */
    private function login(string $provider, User $user): RedirectResponse
    {
        $token = $user->createToken($provider.'-token');

        return redirect('/')->with('token', $token->plainTextToken);
    }
}
