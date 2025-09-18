<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActividadIntento;
use App\Models\Actividad;
use App\Http\Controllers\AlumnoController;

class DebugUltimoIntento extends Command
{
    protected $signature = 'intentos:debug-last';
    protected $description = 'Muestra el último intento y los detalles que calcula el servidor';

    public function handle()
    {
        $intento = ActividadIntento::with('actividad')->orderBy('id', 'desc')->first();
        if (!$intento) {
            $this->error('No hay intentos en la base de datos');
            return 1;
        }

        $actividad = $intento->actividad;
        if (!$actividad) {
            $this->error('Actividad no encontrada para el intento id: ' . $intento->id);
            return 1;
        }

        $respuestas = $intento->respuestas;
        if (is_string($respuestas)) {
            $decoded = json_decode($respuestas, true);
            if (json_last_error() === JSON_ERROR_NONE) $respuestas = $decoded;
        }

        $controller = new AlumnoController();
        $resultado = $controller->evaluarActividadContenido($actividad, $respuestas ?: []);

        $this->info('Intento ID: ' . $intento->id);
        $this->info('Actividad ID: ' . $actividad->id);
        $this->line('Respuestas guardadas:');
        $this->line(json_encode($intento->respuestas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->line('Resultado calculado:');
        $this->line(json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return 0;
    }
}
