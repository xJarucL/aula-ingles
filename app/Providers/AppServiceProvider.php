<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Rate limiting para alumnos
        RateLimiter::for('alumnos', function (Request $request) {
            return [
                // 60 requests por minuto por alumno
                Limit::perMinute(60)->by(
                    $request->session()->get('alumno_datos.id', $request->ip())
                ),
                
                // 10 requests por minuto para procesar respuestas
                Limit::perMinute(10)->by(
                    $request->session()->get('alumno_datos.id', $request->ip())
                )->when(
                    $request->routeIs('alumnos.procesar-respuesta')
                ),
            ];
        });
    }
}