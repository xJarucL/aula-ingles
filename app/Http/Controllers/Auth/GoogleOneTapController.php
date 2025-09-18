<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Google\Client as GoogleClient;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
class GoogleOneTapController extends Controller
{
    public function handle(Request $request)
    {
        // rate limit by IP (added)
        $ipKey = 'onetap:ip:' . $request->ip();
        if (RateLimiter::tooManyAttempts($ipKey, 20)) {
            return redirect()->route('login')->with('auth_fail', 'Demasiados intentos. Intenta de nuevo en unos segundos.');
        }
        RateLimiter::hit($ipKey, 60);
        $idToken = $request->input('credential');
        if (!$idToken) {
            return back()->with('auth_fail', 'No se recibió el token de Google.');
        }

        $clientId = (string) config('services.google.client_id');

        // HTTP client con CA bundle (en local toleramos verify=false)
        $certPath = storage_path('certs/cacert.pem');
        $http = is_file($certPath)
            ? new GuzzleClient(['verify' => $certPath, 'timeout' => 10, 'connect_timeout' => 5])
            : new GuzzleClient(['verify' => false, 'timeout' => 10, 'connect_timeout' => 5]);

        $google = new GoogleClient(['client_id' => $clientId]);
        $google->setHttpClient($http);

        try {
            $payload = $google->verifyIdToken($idToken);
        } catch (\Throwable $e) {
            return back()->with('auth_fail', 'Error verificando token. Intenta de nuevo.');
        }

        if (!$payload) {
            return back()->with('auth_fail', 'Token inválido o expirado.');
        }

        $issOk = in_array($payload['iss'] ?? '', ['accounts.google.com', 'https://accounts.google.com'], true);
        $audOk = ($payload['aud'] ?? null) === $clientId;
        if (!$issOk || !$audOk) {
            return back()->with('auth_fail', 'El token no corresponde al cliente configurado.');
        }

        $email = strtolower($payload['email'] ?? '');
        // rate limit by email (added)
        if (!empty($email)) {
            $mailKey = 'onetap:mail:' . Str::lower($email);
            if (RateLimiter::tooManyAttempts($mailKey, 10)) {
                return redirect()->route('login')->with('auth_fail', 'Demasiados intentos con este correo. Espera un momento.');
            }
            RateLimiter::hit($mailKey, 60);
        }
        if (!$email) {
            return back()->with('auth_fail', 'La cuenta de Google no trae email.');
        }

        logger()->info('ALLOW DEBUG', [
            'email' => $email,
            'ADMIN' => env('ADMIN_GOOGLE_EMAIL'),
            'DOMAINS' => env('ALLOWED_GOOGLE_DOMAINS'),
            'EMAILS' => env('ALLOWED_GOOGLE_EMAILS'),
        ]);


        // Allowlist simple
        $allowedEmails = array_values(array_filter(array_map(fn($x) => strtolower(trim($x)), explode(',', (string) env('ALLOWED_GOOGLE_EMAILS', '')))));
        $allowedDomains = array_values(array_filter(array_map(fn($x) => strtolower(trim($x)), explode(',', (string) env('ALLOWED_GOOGLE_DOMAINS', '')))));
        $adminEmail = strtolower(trim((string) env('ADMIN_GOOGLE_EMAIL', '')));

        $domain = substr(strrchr($email, '@') ?: '', 1);
        $isAllowed = false;

        if (!empty($allowedEmails) && in_array($email, $allowedEmails, true)) {
            $isAllowed = true;
        }
        if (!$isAllowed && !empty($allowedDomains) && in_array($domain, $allowedDomains, true)) {
            $isAllowed = true;
        }
        if (!$isAllowed && $adminEmail && $email === $adminEmail) {
            $isAllowed = true;
        }

        if (!$isAllowed) {
            return redirect()->route('profesores.login')->with('auth_fail', 'Usuario no autorizado');
        }

        $rol = ($adminEmail && $email === $adminEmail) ? 'admin' : 'docente';

        // maintenance flag: allow only admin (added)
        if (filter_var(env('AUTH_MAINTENANCE'), FILTER_VALIDATE_BOOL)) {
            $admin = trim((string) env('ADMIN_GOOGLE_EMAIL'));
            if (empty($admin) || strcasecmp($email, $admin) !== 0) {
                return redirect()->route('login')->with('auth_fail', 'Inicio de sesión temporalmente deshabilitado.');
            }
        }
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $payload['name'] ?? 'Docente',
                'password' => bcrypt(Str::random(32)),
                'email_verified_at' => now(),
                'rol' => $rol,
            ]
        );

        Auth::login($user, true);

        return redirect()->route('profesores.panel')->with('auth_ok', 'Sesión iniciada');
    }
}