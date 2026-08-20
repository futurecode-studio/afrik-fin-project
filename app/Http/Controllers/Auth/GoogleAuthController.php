<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable) {
            return redirect()
                ->route('connexion')
                ->withErrors(['email' => 'Connexion Google interrompue. Réessayez.']);
        }

        $email = (string) $googleUser->getEmail();
        if ($email === '') {
            return redirect()
                ->route('connexion')
                ->withErrors(['email' => 'Google n’a pas transmis d’adresse email.']);
        }

        $user = User::query()
            ->where('google_id', $googleUser->getId())
            ->orWhere('email', $email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: Str::before($email, '@'),
                'email' => $email,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(48)),
                'role' => 'client',
                'google_id' => $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar(),
            ]);
            $user->assignRole('client');
        } else {
            $user->forceFill([
                'google_id' => $user->google_id ?: $googleUser->getId(),
                'google_avatar' => $googleUser->getAvatar() ?: $user->google_avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ])->save();
        }

        Auth::login($user, true);
        session()->regenerate();

        $user->forceFill(['last_login_at' => now()])->save();
        UserActivityLog::record($user->id, UserActivityLog::LOGIN, 'Connexion Google réussie');

        if ($user->isAdmin() || $user->can('dashboard.view')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isClient()) {
            return redirect()->route('client.dashboard');
        }

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()
            ->route('connexion')
            ->withErrors(['email' => 'Ce compte n’a pas de rôle valide. Contactez le support.']);
    }
}
