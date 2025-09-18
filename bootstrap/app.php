<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registrar middlewares de alumnos
        $middleware->alias([
            'acceso.alumno' => \App\Http\Middleware\AccesoAlumnoMiddleware::class,
            'validar.cuatrimestre.parcial' => \App\Http\Middleware\ValidarCuatrimestreParcialMiddleware::class,
            'horario.academico' => \App\Http\Middleware\HorarioAcademicoMiddleware::class,
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,

            
        ]);
        

        // Rate limiting para alumnos (opcional)
        $middleware->throttleApi('alumnos');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();