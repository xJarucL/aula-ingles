<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class EnsureUserHasRole
{
    /**
     * Uso: ->middleware('role:docente,admin')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $loginRoute = Route::has('profesores.login') ? 'profesores.login' : 'login';

        if (!Auth::check()) {
            return redirect()->route($loginRoute)
                ->with('auth_fail', 'Necesitas iniciar sesión');
        }

        // $rol = Auth::user()->rol ?? null;

        // if (!in_array($rol, $roles, true)) {
        //     return redirect()->route($loginRoute)
        //         ->with('auth_fail', 'Usuario no autorizado');
        // }

        return $next($request);
    }
}
