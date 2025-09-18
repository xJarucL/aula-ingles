<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Actividad;
use App\Models\ActividadIntento;
use App\Models\Alumno;

class DebugActividadesCommand extends Command
{
    protected $signature = 'debug:actividades 
                          {--actividad=* : IDs específicos de actividades a debuggear}
                          {--alumno= : Nombre del alumno para simular}
                          {--test : Ejecutar pruebas de evaluación}';
                          
    protected $description = 'Debug y pruebas del sistema de actividades';

    public function handle()
    {
        $this->info('🔧 SISTEMA DE DEBUG DE ACTIVIDADES');
        $this->info(str_repeat('=', 50));
        
        if ($this->option('test')) {
            $this->ejecutarPruebas();
            return;
        }

        $actividadIds = $this->option('actividad');
        
        if (empty($actividadIds)) {
            $this->mostrarResumenGeneral();
            $this->preguntarPorActividad();
        } else {
            foreach ($actividadIds as $id) {
                $this->debugActividad($id);
            }
        }
    }

    private function mostrarResumenGeneral()
    {
        $actividades = Actividad::with('parcial.cuatrimestre')->get();
        
        $this->info("📊 RESUMEN GENERAL");
        $this->info("Total de actividades: " . $actividades->count());
        $this->info("");
        
        $this->table(
            ['ID', 'Nombre', 'Tipo', 'Items', 'Parcial', 'Activa'],
            $actividades->map(function ($actividad) {
                $contenido = $actividad->contenido;
                $tipo = is_array($contenido) ? ($contenido['tipo'] ?? 'manual') : 'manual';
                
                $items = 0;
                if ($tipo === 'quiz' && isset($contenido['contenido']['preguntas'])) {
                    $items = count($contenido['contenido']['preguntas']);
                } elseif ($tipo === 'completar' && isset($contenido['contenido']['ejercicios'])) {
                    $items = count($contenido['contenido']['ejercicios']);
                }
                
                return [
                    $actividad->id,
                    substr($actividad->nombre, 0, 30) . (strlen($actividad->nombre) > 30 ? '...' : ''),
                    $tipo,
                    $items,
                    $actividad->parcial ? substr($actividad->parcial->nombre, 0, 15) : 'N/A',
                    $actividad->activa ? '✅' : '❌'
                ];
            })->toArray()
        );
    }

    private function preguntarPorActividad()
    {
        $actividadId = $this->ask('Ingresa el ID de la actividad que quieres debuggear (o "exit" para salir)');
        
        if ($actividadId === 'exit') {
            return;
        }
        
        if (is_numeric($actividadId)) {
            $this->debugActividad($actividadId);
            $this->preguntarPorActividad();
        } else {
            $this->error('Por favor ingresa un número válido');
            $this->preguntarPorActividad();
        }
    }

    private function debugActividad($actividadId)
    {
        try {
            $actividad = Actividad::with('parcial.cuatrimestre')->findOrFail($actividadId);
            
            $this->info("\n" . str_repeat('=', 50));
            $this->info("🔍 DEBUG ACTIVIDAD ID: {$actividadId}");
            $this->info(str_repeat('=', 50));
            
            $this->info("📝 Nombre: {$actividad->nombre}");
            $this->info("📖 Descripción: " . ($actividad->descripcion ?: 'Sin descripción'));
            $this->info("📚 Parcial: " . ($actividad->parcial->nombre ?? 'N/A'));
            $this->info("🎓 Cuatrimestre: " . ($actividad->parcial->cuatrimestre->nombre ?? 'N/A'));
            $this->info("✅ Activa: " . ($actividad->activa ? 'SÍ' : 'NO'));
            $this->info("");
            
            // Analizar contenido
            $this->analizarContenido($actividad);
            
            // Mostrar intentos recientes
            $this->mostrarIntentosRecientes($actividad);
            
            // Ofrecer simulación
            if ($this->confirm('¿Quieres simular una respuesta?')) {
                $this->simularRespuesta($actividad);
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Error al cargar actividad {$actividadId}: " . $e->getMessage());
        }
    }

    private function analizarContenido($actividad)
    {
        $contenido = $actividad->contenido;
        
        $this->info("🔍 ANÁLISIS DEL CONTENIDO");
        $this->info("Tipo de dato: " . gettype($contenido));
        
        if (!is_array($contenido)) {
            $this->error("❌ El contenido no es un array válido");
            return;
        }
        
        $tipo = $contenido['tipo'] ?? 'no definido';
        $this->info("📋 Tipo de actividad: {$tipo}");
        $this->info("🔧 Estructura: " . implode(', ', array_keys($contenido)));
        
        if ($tipo === 'quiz') {
            $this->analizarQuiz($contenido);
        } elseif ($tipo === 'completar') {
            $this->analizarCompletar($contenido);
        }
        
        $this->info("");
    }

    private function analizarQuiz($contenido)
    {
        // Buscar preguntas en todas las ubicaciones posibles
        $preguntas = [];
        
        if (isset($contenido['contenido']['preguntas'])) {
            $preguntas = $contenido['contenido']['preguntas'];
            $this->info("✅ Preguntas encontradas en: contenido.contenido.preguntas");
        } elseif (isset($contenido['preguntas'])) {
            $preguntas = $contenido['preguntas'];
            $this->info("✅ Preguntas encontradas en: contenido.preguntas");
        } else {
            $this->error("❌ No se encontraron preguntas en el quiz");
            return;
        }
        
        $this->info("📊 Total de preguntas: " . count($preguntas));
        
        if (!empty($preguntas)) {
            $this->info("🔍 Estructura de la primera pregunta:");
            $primeraPregunta = $preguntas[0];
            foreach ($primeraPregunta as $key => $value) {
                if (is_array($value)) {
                    $this->info("   {$key}: [array con " . count($value) . " elementos]");
                } else {
                    $this->info("   {$key}: " . substr($value, 0, 50));
                }
            }
        }
    }

    private function analizarCompletar($contenido)
    {
        // Buscar ejercicios en todas las ubicaciones posibles
        $ejercicios = [];
        
        if (isset($contenido['contenido']['ejercicios'])) {
            $ejercicios = $contenido['contenido']['ejercicios'];
            $this->info("✅ Ejercicios encontrados en: contenido.contenido.ejercicios");
        } elseif (isset($contenido['ejercicios'])) {
            $ejercicios = $contenido['ejercicios'];
            $this->info("✅ Ejercicios encontrados en: contenido.ejercicios");
        } elseif (isset($contenido['frases'])) {
            $ejercicios = $contenido['frases'];
            $this->info("✅ Ejercicios encontrados en: contenido.frases");
        } else {
            $this->error("❌ No se encontraron ejercicios para completar");
            return;
        }
        
        $this->info("📊 Total de ejercicios: " . count($ejercicios));
        
        if (!empty($ejercicios)) {
            $this->info("🔍 Estructura del primer ejercicio:");
            $primerEjercicio = $ejercicios[0];
            foreach ($primerEjercicio as $key => $value) {
                if (is_array($value)) {
                    $this->info("   {$key}: [array con " . count($value) . " elementos]");
                } else {
                    $this->info("   {$key}: {$value}");
                }
            }
        }
    }

    private function mostrarIntentosRecientes($actividad)
    {
        $intentos = ActividadIntento::where('actividad_id', $actividad->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        if ($intentos->count() > 0) {
            $this->info("📈 ÚLTIMOS 5 INTENTOS:");
            
            $this->table(
                ['Fecha', 'Alumno', 'Puntaje', 'Total', '%', 'Tiempo'],
                $intentos->map(function ($intento) {
                    $porcentaje = $intento->total_preguntas > 0 
                        ? round(($intento->puntaje / $intento->total_preguntas) * 100, 1) 
                        : 0;
                        
                    return [
                        $intento->created_at->format('d/m/Y H:i'),
                        substr($intento->alumno_nombre, 0, 15),
                        $intento->puntaje,
                        $intento->total_preguntas,
                        $porcentaje . '%',
                        $intento->tiempo_completado . 's'
                    ];
                })->toArray()
            );
        } else {
            $this->info("📈 No hay intentos registrados para esta actividad");
        }
    }

    private function simularRespuesta($actividad)
    {
        $contenido = $actividad->contenido;
        $tipo = $contenido['tipo'] ?? 'manual';
        
        if ($tipo === 'quiz') {
            $this->simularQuiz($actividad);
        } elseif ($tipo === 'completar') {
            $this->simularCompletar($actividad);
        } else {
            $this->error("No se puede simular respuesta para el tipo: {$tipo}");
        }
    }

    private function simularQuiz($actividad)
    {
        $this->info("🎯 SIMULACIÓN DE QUIZ");
        
        // Usar el mismo método del controlador para evaluar
        $controlador = new \App\Http\Controllers\AlumnoController();
        
        // Crear respuestas de prueba
        $respuestasPrueba = [];
        $opciones = ['A', 'B', 'C', 'D'];
        
        $totalPreguntas = $this->contarPreguntas($actividad->contenido);
        
        for ($i = 0; $i < $totalPreguntas; $i++) {
            $respuestasPrueba[$i] = $opciones[array_rand($opciones)];
        }
        
        $this->info("🎲 Respuestas aleatorias generadas: " . json_encode($respuestasPrueba));
        
        // Evaluar usando reflexión para acceder al método privado
        $reflection = new \ReflectionClass($controlador);
        $method = $reflection->getMethod('evaluarRespuestas');
        $method->setAccessible(true);
        
        $resultado = $method->invoke($controlador, $actividad, $respuestasPrueba);
        
        $this->info("📊 RESULTADO DE LA SIMULACIÓN:");
        $this->info("   Puntaje: {$resultado['puntaje']}/{$resultado['total']}");
        $this->info("   Porcentaje: {$resultado['porcentaje']}%");
        $this->info("   Tipo: {$resultado['tipo']}");
    }

    private function simularCompletar($actividad)
    {
        $this->info("✏️ SIMULACIÓN DE COMPLETAR");
        
        $controlador = new \App\Http\Controllers\AlumnoController();
        
        // Crear respuestas de prueba (algunas correctas, otras incorrectas)
        $respuestasPrueba = [];
        $totalEjercicios = $this->contarEjercicios($actividad->contenido);
        
        for ($i = 0; $i < $totalEjercicios; $i++) {
            $respuestasPrueba[$i] = rand(0, 1) ? 'correcta' : 'incorrecta'; // Aleatorio
        }
        
        $this->info("🎲 Respuestas de prueba generadas: " . json_encode($respuestasPrueba));
        
        // Evaluar
        $reflection = new \ReflectionClass($controlador);
        $method = $reflection->getMethod('evaluarRespuestas');
        $method->setAccessible(true);
        
        $resultado = $method->invoke($controlador, $actividad, $respuestasPrueba);
        
        $this->info("📊 RESULTADO DE LA SIMULACIÓN:");
        $this->info("   Puntaje: {$resultado['puntaje']}/{$resultado['total']}");
        $this->info("   Porcentaje: {$resultado['porcentaje']}%");
        $this->info("   Tipo: {$resultado['tipo']}");
    }

    private function contarPreguntas($contenido)
    {
        if (isset($contenido['contenido']['preguntas'])) {
            return count($contenido['contenido']['preguntas']);
        }
        if (isset($contenido['preguntas'])) {
            return count($contenido['preguntas']);
        }
        return 0;
    }

    private function contarEjercicios($contenido)
    {
        if (isset($contenido['contenido']['ejercicios'])) {
            return count($contenido['contenido']['ejercicios']);
        }
        if (isset($contenido['ejercicios'])) {
            return count($contenido['ejercicios']);
        }
        if (isset($contenido['frases'])) {
            return count($contenido['frases']);
        }
        return 0;
    }

    private function ejecutarPruebas()
    {
        $this->info("🧪 EJECUTANDO PRUEBAS AUTOMÁTICAS");
        $this->info(str_repeat('=', 50));
        
        $actividades = Actividad::all();
        $errores = 0;
        $exitos = 0;
        
        foreach ($actividades as $actividad) {
            try {
                $this->info("Probando: {$actividad->nombre} (ID: {$actividad->id})");
                
                $controlador = new \App\Http\Controllers\AlumnoController();
                $reflection = new \ReflectionClass($controlador);
                $method = $reflection->getMethod('evaluarRespuestas');
                $method->setAccessible(true);
                
                // Crear respuestas de prueba
                $respuestasPrueba = ['0' => 'A', '1' => 'B', '2' => 'C'];
                
                $resultado = $method->invoke($controlador, $actividad, $respuestasPrueba);
                
                if (is_array($resultado) && isset($resultado['total']) && isset($resultado['puntaje'])) {
                    $this->info("✅ OK - Puntaje: {$resultado['puntaje']}/{$resultado['total']}");
                    $exitos++;
                } else {
                    $this->error("❌ Resultado inválido");
                    $errores++;
                }
                
            } catch (\Exception $e) {
                $this->error("❌ Error: " . $e->getMessage());
                $errores++;
            }
        }
        
        $this->info(str_repeat('=', 50));
        $this->info("🏁 RESULTADOS DE LAS PRUEBAS");
        $this->info("✅ Exitosas: {$exitos}");
        $this->info("❌ Errores: {$errores}");
        $this->info("📊 Total: " . ($exitos + $errores));
    }
}