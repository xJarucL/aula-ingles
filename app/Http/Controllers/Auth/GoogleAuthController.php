<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless(false)
            ->scopes(['email', 'profile'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless(false)->user();
        } catch (\Throwable $e) {
            Log::error('Google callback error', ['e' => $e->getMessage()]);
            return redirect()->route('login')->withErrors(['auth' => 'No se pudo autenticar con Google.']);
        }

        $email = strtolower($googleUser->getEmail());
        $allowedEmails  = collect(explode(',', (string) env('ALLOWED_GOOGLE_EMAILS')))
                            ->map(fn($e) => trim(strtolower($e)))->filter()->values();
        $allowedDomains = collect(explode(',', (string) env('ALLOWED_GOOGLE_DOMAINS')))
                            ->map(fn($d) => trim(strtolower($d)))->filter()->values();

        $domain = Str::after($email, '@');

        $isAllowed = $allowedEmails->contains($email)
            || ($allowedDomains->isNotEmpty() && $allowedDomains->contains($domain));

        Log::info('ALLOW DEBUG', [
            'email'   => $email,
            'ADMIN'   => env('ADMIN_EMAIL'),
            'DOMAINS' => $allowedDomains->implode(','),
            'EMAILS'  => $allowedEmails->implode(','),
            'domain'  => $domain,
            'allowed' => $isAllowed,
        ]);

        if (!$isAllowed) {
            return redirect()->route('login')->withErrors([
                'auth' => 'Tu cuenta no está autorizada para acceder.'
            ]);
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'              => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Usuario',
                'password'          => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
            ]
        );

        // Marcar admin opcionalmente
        if (method_exists($user, 'is_admin') || array_key_exists('is_admin', $user->getAttributes())) {
            $user->is_admin = $email === strtolower((string) env('ADMIN_EMAIL'));
            $user->save();
        }

        auth()->login($user, remember: true);

        return redirect()->intended('/'); // cambia al dashboard que uses
    }
}
