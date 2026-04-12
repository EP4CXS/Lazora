<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(string $provider)
    {
        if (! in_array($provider, ['google'])) {
            return redirect()->route('login')->with('status', 'Unsupported login provider.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Google and handle login/registration.
     */
    public function callback(string $provider)
    {
        if (! in_array($provider, ['google'])) {
            return redirect()->route('login')->with('status', 'Unsupported login provider.');
        }

        try {
            $socialiteUser = Socialite::driver($provider)->user();
        } catch (\Exception) {
            return redirect()->route('login')->with('status', 'Unable to authenticate with ' . ucfirst($provider) . '. Please try again.');
        }

        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialiteUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;

            $socialAccount->update([
                'token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
            ]);

            Auth::login($user);

            return redirect()->intended(config('fortify.home'));
        }

        $existingUser = User::where('email', $socialiteUser->getEmail())->first();

        if ($existingUser) {
            $existingUser->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
                'token' => $socialiteUser->token,
                'refresh_token' => $socialiteUser->refreshToken,
            ]);

            Auth::login($existingUser);

            return redirect()->intended(config('fortify.home'));
        }

        $user = User::create([
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
            'password' => null,
            'email_verified_at' => now(),
            'role' => 'customer',
        ]);

        $user->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $socialiteUser->getId(),
            'token' => $socialiteUser->token,
            'refresh_token' => $socialiteUser->refreshToken,
        ]);

        Auth::login($user);

        return redirect()->intended(config('fortify.home'));
    }
}
