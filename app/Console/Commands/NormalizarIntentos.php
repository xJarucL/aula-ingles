<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ActividadIntento;
use App\Models\Actividad;
use App\Http\Controllers\AlumnoController;

class NormalizarIntentos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'intentos:normalizar {--limit=0 : Limitar el número de intentos procesados}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normaliza intentos históricos recalculando respuestas y detalles según la lógica actual';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $limit = (int) $this->option('limit');

        $query = ActividadIntento::query()->orderBy('id', 'asc');
        if ($limit > 0) $query->limit($limit);

        $total = $query->count();
        $this->info("Procesando $total intentos...");

        $controller = new AlumnoController();
        $processed = 0;

        $query->chunk(100, function($intentos) use (&$processed, $controller) {
            foreach ($intentos as $intento) {
                $actividad = Actividad::find($intento->actividad_id);
                if (!$actividad) continue;

                // Asegurar que respuestas estén en array
                $respuestas = $intento->respuestas;
                if (is_string($respuestas)) {
                    $decoded = json_decode($respuestas, true);
                    if (json_last_error() === JSON_ERROR_NONE) $respuestas = $decoded;
                }

                $resultado = $controller->evaluarActividadContenido($actividad, $respuestas ?: []);

                // Actualizar intento
                $intento->puntaje = $resultado['puntaje'];
                $intento->total_preguntas = $resultado['total_preguntas'];
                $intento->porcentaje = $resultado['porcentaje'];
                $intento->respuestas = $resultado['respuestas'];
                $intento->save();

                $processed++;
            }
        });

        $this->info("Hecho. Intentos procesados: $processed");

        return 0;
    }
}
