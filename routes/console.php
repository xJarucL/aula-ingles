<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\RepararActividadesRespuestas;

/*
|--------------------------------------------------------------------------
| Console Routes - Laravel 12
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Registrar comando de reparación de actividades
Artisan::command('actividades:reparar-respuestas {--force}', function () {
    $this->info('🔧 REPARANDO ACTIVIDADES SIN RESPUESTAS CORRECTAS');
    
    $actividades = \App\Models\Actividad::all();
    $actividadesReparadas = 0;

    foreach ($actividades as $actividad) {
        $contenido = $actividad->contenido;
        $necesitaReparacion = false;

        if (!is_array($contenido)) continue;

        // Buscar preguntas
        $preguntas = null;
        if (isset($contenido['contenido']['preguntas'])) {
            $preguntas = &$contenido['contenido']['preguntas'];
        } elseif (isset($contenido['preguntas'])) {
            $preguntas = &$contenido['preguntas'];
        }

        if (!$preguntas || !is_array($preguntas)) continue;

        // Reparar cada pregunta
        foreach ($preguntas as $index => &$pregunta) {
            if (!isset($pregunta['correcta'])) {
                // Buscar respuesta correcta en otros campos
                if (isset($pregunta['respuesta'])) {
                    $pregunta['correcta'] = strtoupper($pregunta['respuesta']);
                } elseif (isset($pregunta['answer'])) {
                    $pregunta['correcta'] = strtoupper($pregunta['answer']);
                } elseif (isset($pregunta['correct'])) {
                    $pregunta['correcta'] = strtoupper($pregunta['correct']);
                } else {
                    // Asignar por defecto
                    $pregunta['correcta'] = 'A';
                }
                $necesitaReparacion = true;
                $this->line("✅ Actividad {$actividad->id}, Pregunta {$index}: Reparada");
            }
        }

        if ($necesitaReparacion) {
            $actividad->contenido = $contenido;
            $actividad->save();
            $actividadesReparadas++;
        }
    }

    $this->info("🎉 Actividades reparadas: {$actividadesReparadas}");
    return 0;
})->purpose('Reparar actividades sin respuestas correctas');