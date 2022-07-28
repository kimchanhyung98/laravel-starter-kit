<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Social service.
     *
     * @param  string  $provider
     * @return RedirectResponse
     */
    public function handleProviderCallback(string $provider): RedirectResponse
    {
        // $user = Socialite::driver($provider)->user();

        // The stateless method may be used to disable session state verification
        $social = Socialite::driver($provider)->stateless()->user();

        // $user->token;

        if ($user = User::where('email', $social->getEmail())->first()) {
            return $this->login($provider, $user);
        }
        ddd($social);
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
            'name' => $social->name,
            'email' => $social->email,
            'username' => $social->nickname,
            'provider' => $provider,
            'provider_id' => $social->id,
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
