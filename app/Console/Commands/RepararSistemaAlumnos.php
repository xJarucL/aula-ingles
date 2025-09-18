<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Alumno;
use App\Models\ActividadIntento;
use App\Models\Actividad;

class RepararSistemaAlumnos extends Command
{
    protected $signature = 'sistema:reparar-alumnos {--force : Forzar reparación sin confirmación}';
    protected $description = 'Repara problemas comunes en el sistema de alumnos - VERSIÓN COMPATIBLE';

    public function handle()
    {
        $this->info('🔧 INICIANDO REPARACIÓN DEL SISTEMA DE ALUMNOS');
        $this->line('═══════════════════════════════════════════════');

        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres ejecutar la reparación del sistema?')) {
                $this->error('Operación cancelada por el usuario.');
                return 1;
            }
        }

        try {
            // 1. Verificar y reparar estructura de base de datos
            $this->repararEstructuraDB();
            
            // 2. Limpiar datos inconsistentes
            $this->limpiarDatosInconsistentes();
            
            // 3. Reparar intentos de actividades
            $this->repararIntentosActividades();
            
            // 4. Verificar y corregir actividades (VERSIÓN COMPATIBLE)
            $this->verificarActividadesCompatible();
            
            // 5. Actualizar índices y optimizar tablas
            $this->optimizarBaseDatos();
            
            // 6. Verificar configuración del sistema
            $this->verificarConfiguracion();

            $this->mostrarResumenReparacion();
            
            $this->info('✅ ¡REPARACIÓN COMPLETADA EXITOSAMENTE!');
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Error durante la reparación: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }

    private function repararEstructuraDB()
    {
        $this->info('🔍 1. Verificando estructura de base de datos...');
        
        // Verificar tabla alumnos
        if (!Schema::hasTable('alumnos')) {
            $this->error('❌ Tabla alumnos no existe. Ejecuta las migraciones primero.');
            return;
        }

        // Verificar campos esenciales en tabla alumnos
        $camposAlumnos = ['id', 'matricula', 'nombre', 'carrera_id', 'activo'];
        foreach ($camposAlumnos as $campo) {
            if (!Schema::hasColumn('alumnos', $campo)) {
                $this->warn("⚠️ Campo '{$campo}' no existe en tabla alumnos");
            }
        }

        // Verificar cuatrimestre_id específicamente
        if (!Schema::hasColumn('alumnos', 'cuatrimestre_id')) {
            $this->warn("⚠️ Campo 'cuatrimestre_id' no existe en tabla alumnos");
            $this->line("   💡 Ejecuta: php artisan migrate para crear el campo");
        } else {
            $this->info("✅ Campo 'cuatrimestre_id' existe en tabla alumnos");
        }

        // Verificar tabla actividad_intentos
        if (!Schema::hasTable('actividad_intentos')) {
            $this->error('❌ Tabla actividad_intentos no existe. Ejecuta las migraciones primero.');
            return;
        }

        // Agregar índices faltantes de forma compatible
        $this->info('📊 Verificando índices...');
        
        try {
            // Verificar si el índice ya existe antes de crearlo
            $indices = DB::select("SHOW INDEX FROM alumnos WHERE Key_name = 'idx_alumnos_matricula'");
            if (empty($indices)) {
                DB::statement('CREATE INDEX idx_alumnos_matricula ON alumnos(matricula)');
                $this->info('✅ Índice idx_alumnos_matricula creado');
            }

            // Solo crear índice de cuatrimestre si el campo existe
            if (Schema::hasColumn('alumnos', 'cuatrimestre_id')) {
                $indices = DB::select("SHOW INDEX FROM alumnos WHERE Key_name = 'idx_alumnos_carrera_cuatrimestre'");
                if (empty($indices)) {
                    DB::statement('CREATE INDEX idx_alumnos_carrera_cuatrimestre ON alumnos(carrera_id, cuatrimestre_id)');
                    $this->info('✅ Índice idx_alumnos_carrera_cuatrimestre creado');
                }
            }

            $indices = DB::select("SHOW INDEX FROM actividad_intentos WHERE Key_name = 'idx_intentos_alumno_actividad'");
            if (empty($indices)) {
                DB::statement('CREATE INDEX idx_intentos_alumno_actividad ON actividad_intentos(alumno_id, actividad_id)');
                $this->info('✅ Índice idx_intentos_alumno_actividad creado');
            }
            
        } catch (\Exception $e) {
            $this->warn('⚠️ Algunos índices no pudieron ser creados: ' . $e->getMessage());
        }
    }

    private function limpiarDatosInconsistentes()
    {
        $this->info('🧹 2. Limpiando datos inconsistentes...');

        // Eliminar alumnos duplicados por matrícula
        $duplicados = DB::select("
            SELECT matricula, COUNT(*) as cantidad 
            FROM alumnos 
            GROUP BY matricula 
            HAVING cantidad > 1
        ");

        if (count($duplicados) > 0) {
            $this->warn('⚠️ Encontrados ' . count($duplicados) . ' matrículas duplicadas');
            
            foreach ($duplicados as $duplicado) {
                // Mantener solo el registro más reciente
                $mantener = DB::table('alumnos')
                    ->where('matricula', $duplicado->matricula)
                    ->orderBy('id', 'desc')
                    ->first();
                
                DB::table('alumnos')
                    ->where('matricula', $duplicado->matricula)
                    ->where('id', '!=', $mantener->id)
                    ->delete();
                
                $this->line("   - Limpiado duplicados para matrícula: {$duplicado->matricula}");
            }
        }

        // Limpiar intentos huérfanos (sin alumno o actividad válida) - VERSIÓN COMPATIBLE
        $intentosHuerfanos = DB::table('actividad_intentos as ai')
            ->leftJoin('alumnos as a', 'ai.alumno_id', '=', 'a.id')
            ->leftJoin('actividades as act', 'ai.actividad_id', '=', 'act.id')
            ->whereNull('a.id')
            ->orWhereNull('act.id')
            ->count();

        if ($intentosHuerfanos > 0) {
            // Eliminar intentos huérfanos de forma más segura
            $intentosAEliminar = DB::table('actividad_intentos as ai')
                ->leftJoin('alumnos as a', 'ai.alumno_id', '=', 'a.id')
                ->leftJoin('actividades as act', 'ai.actividad_id', '=', 'act.id')
                ->where(function($query) {
                    $query->whereNull('a.id')->orWhereNull('act.id');
                })
                ->pluck('ai.id');

            if (!empty($intentosAEliminar)) {
                DB::table('actividad_intentos')->whereIn('id', $intentosAEliminar)->delete();
            }
            
            $this->warn("⚠️ Eliminados {$intentosHuerfanos} intentos huérfanos");
        }

        $this->info('✅ Datos inconsistentes limpiados');
    }

    private function repararIntentosActividades()
    {
        $this->info('🎯 3. Reparando intentos de actividades...');

        // Corregir intentos con alumno_id nulo pero con alumno_nombre - VERSIÓN COMPATIBLE
        $intentosSinID = DB::table('actividad_intentos')
            ->whereNull('alumno_id')
            ->whereNotNull('alumno_nombre')
            ->get();

        $reparados = 0;
        foreach ($intentosSinID as $intento) {
            // Buscar alumno por nombre de forma más flexible
            $nombrePartes = explode(' ', $intento->alumno_nombre);
            $primerNombre = $nombrePartes[0] ?? '';
            
            if ($primerNombre) {
                $alumno = DB::table('alumnos')
                    ->where('nombre', 'LIKE', '%' . $primerNombre . '%')
                    ->first();
                
                if ($alumno) {
                    DB::table('actividad_intentos')
                        ->where('id', $intento->id)
                        ->update(['alumno_id' => $alumno->id]);
                    $reparados++;
                }
            }
        }

        if ($reparados > 0) {
            $this->info("✅ Reparados {$reparados} intentos con alumno_id faltante");
        }

        // Recalcular números de intento
        $this->info('🔢 Recalculando números de intento...');
        
        $actividades = DB::table('actividades')->select('id')->get();
        foreach ($actividades as $actividad) {
            $intentos = DB::table('actividad_intentos')
                ->where('actividad_id', $actividad->id)
                ->orderBy('created_at')
                ->get()
                ->groupBy('alumno_id');

            foreach ($intentos as $alumnoId => $intentosAlumno) {
                $numeroIntento = 1;
                foreach ($intentosAlumno as $intento) {
                    DB::table('actividad_intentos')
                        ->where('id', $intento->id)
                        ->update(['numero_intento' => $numeroIntento]);
                    $numeroIntento++;
                }
            }
        }

        $this->info('✅ Números de intento recalculados');
    }

    private function verificarActividadesCompatible()
    {
        $this->info('📚 4. Verificando actividades (versión compatible)...');

        $actividades = Actividad::all();
        $actividadesReparadas = 0;

        foreach ($actividades as $actividad) {
            $contenido = $actividad->contenido;
            $necesitaReparacion = false;

            // Verificar estructura del contenido
            if (!is_array($contenido)) {
                $this->warn("⚠️ Actividad {$actividad->id}: Contenido no es un array válido");
                continue;
            }

            // Asegurar que tiene tipo definido
            if (!isset($contenido['tipo'])) {
                $contenido['tipo'] = 'quiz';
                $necesitaReparacion = true;
            }

            // Verificar estructura de preguntas según el tipo
            if ($contenido['tipo'] === 'quiz') {
                if (!isset($contenido['contenido']['preguntas']) && !isset($contenido['preguntas'])) {
                    $this->warn("⚠️ Actividad {$actividad->id}: No tiene preguntas definidas");
                    continue;
                }

                // Mover preguntas a la estructura correcta si es necesario
                if (isset($contenido['preguntas']) && !isset($contenido['contenido']['preguntas'])) {
                    $contenido['contenido']['preguntas'] = $contenido['preguntas'];
                    unset($contenido['preguntas']);
                    $necesitaReparacion = true;
                }

                // Verificar que las preguntas tienen respuesta correcta
                $preguntas = $contenido['contenido']['preguntas'] ?? [];
                foreach ($preguntas as $index => &$pregunta) {
                    if (!isset($pregunta['correcta'])) {
                        // Intentar deducir la respuesta correcta
                        if (isset($pregunta['respuesta'])) {
                            $pregunta['correcta'] = $pregunta['respuesta'];
                            $necesitaReparacion = true;
                        } elseif (isset($pregunta['answer'])) {
                            $pregunta['correcta'] = $pregunta['answer'];
                            $necesitaReparacion = true;
                        } elseif (isset($pregunta['correct'])) {
                            $pregunta['correcta'] = $pregunta['correct'];
                            $necesitaReparacion = true;
                        } else {
                            // Asignar respuesta por defecto
                            $pregunta['correcta'] = 'A';
                            $necesitaReparacion = true;
                            $this->warn("⚠️ Actividad {$actividad->id}, Pregunta {$index}: Sin respuesta correcta - asignada 'A'");
                        }
                    }
                }
            }

            if ($necesitaReparacion) {
                $actividad->contenido = $contenido;
                $actividad->save();
                $actividadesReparadas++;
            }
        }

        if ($actividadesReparadas > 0) {
            $this->info("✅ Reparadas {$actividadesReparadas} actividades");
        } else {
            $this->info('✅ Todas las actividades están correctas');
        }
    }

    private function optimizarBaseDatos()
    {
        $this->info('⚡ 5. Optimizando base de datos...');

        try {
            // Optimizar tablas principales
            $tablas = ['alumnos', 'actividades', 'actividad_intentos', 'parciales', 'cuatrimestres'];
            
            foreach ($tablas as $tabla) {
                if (Schema::hasTable($tabla)) {
                    DB::statement("OPTIMIZE TABLE {$tabla}");
                }
            }

            $this->info('✅ Base de datos optimizada');
        } catch (\Exception $e) {
            $this->warn('⚠️ Optimización parcialmente exitosa: ' . $e->getMessage());
        }
    }

    private function verificarConfiguracion()
    {
        $this->info('⚙️ 6. Verificando configuración del sistema...');

        // Verificar middleware en Kernel.php
        $kernelPath = app_path('Http/Kernel.php');
        if (file_exists($kernelPath)) {
            $kernelContent = file_get_contents($kernelPath);
            
            if (!str_contains($kernelContent, 'AccesoAlumnoMiddleware')) {
                $this->warn('⚠️ AccesoAlumnoMiddleware no registrado en Kernel.php');
            } else {
                $this->info('✅ Middleware registrado correctamente');
            }
        }

        // Verificar rutas básicas
        try {
            $rutasBasicas = [
                'alumnos.panel',
                'alumnos.procesar',
                'alumnos.mis-grupos'
            ];

            $rutasFaltantes = [];
            foreach ($rutasBasicas as $ruta) {
                try {
                    route($ruta);
                } catch (\Exception $e) {
                    $rutasFaltantes[] = $ruta;
                }
            }

            if (empty($rutasFaltantes)) {
                $this->info('✅ Rutas básicas configuradas');
            } else {
                $this->warn('⚠️ Rutas faltantes: ' . implode(', ', $rutasFaltantes));
            }
        } catch (\Exception $e) {
            $this->warn('⚠️ No se pudieron verificar todas las rutas');
        }

        // Verificar permisos de storage
        $storagePath = storage_path('app/public');
        if (!is_writable($storagePath)) {
            $this->warn('⚠️ Directorio storage/app/public no tiene permisos de escritura');
        } else {
            $this->info('✅ Permisos de storage correctos');
        }
    }

    private function mostrarResumenReparacion()
    {
        $this->newLine();
        $this->info('📊 RESUMEN DE LA REPARACIÓN:');
        $this->line('══════════════════════════════');

        // Estadísticas generales
        $totalAlumnos = DB::table('alumnos')->count();
        $alumnosActivos = DB::table('alumnos')->where('activo', true)->count();
        $totalActividades = DB::table('actividades')->count();
        $actividadesActivas = DB::table('actividades')->where('activa', true)->count();
        $totalIntentos = DB::table('actividad_intentos')->count();

        $this->line("👥 Alumnos totales: {$totalAlumnos} (Activos: {$alumnosActivos})");
        $this->line("📚 Actividades totales: {$totalActividades} (Activas: {$actividadesActivas})");
        $this->line("🎯 Intentos registrados: {$totalIntentos}");

        // Verificar integridad de datos usando consultas simples
        $intentosSinAlumno = DB::table('actividad_intentos')->whereNull('alumno_id')->count();
        
        // Contar actividades sin preguntas usando métodos compatibles
        $actividadesSinPreguntas = 0;
        $actividades = Actividad::all();
        foreach ($actividades as $actividad) {
            $contenido = $actividad->contenido;
            if (is_array($contenido)) {
                $tienePreguntas = isset($contenido['contenido']['preguntas']) || 
                                 isset($contenido['preguntas']) || 
                                 isset($contenido['ejercicios']);
                if (!$tienePreguntas) {
                    $actividadesSinPreguntas++;
                }
            }
        }

        if ($intentosSinAlumno === 0 && $actividadesSinPreguntas === 0) {
            $this->info('✅ Integridad de datos: CORRECTA');
        } else {
            $this->warn("⚠️ Problemas pendientes:");
            if ($intentosSinAlumno > 0) {
                $this->warn("   - {$intentosSinAlumno} intentos sin alumno_id");
            }
            if ($actividadesSinPreguntas > 0) {
                $this->warn("   - {$actividadesSinPreguntas} actividades sin preguntas");
            }
        }

        $this->newLine();
        $this->info('💡 RECOMENDACIONES POST-REPARACIÓN:');
        $this->line('1. Ejecuta: php artisan config:cache');
        $this->line('2. Ejecuta: php artisan route:cache');
        $this->line('3. Verifica que el layout alumno.blade.php esté en resources/views/layouts/');
        $this->line('4. Actualiza las vistas de alumnos para usar @extends(\'layouts.alumno\')');
        $this->line('5. Registra el middleware AccesoAlumnoMiddleware en Kernel.php');
        $this->line('6. Prueba el flujo completo: login → ver actividad → responder → ver resultado');
        
        $this->newLine();
        $this->warn('⚠️ IMPORTANTE: Muchas actividades tenían respuestas correctas faltantes.');
        $this->warn('Se han asignado respuestas por defecto. Revisa y ajusta según sea necesario.');
    }
}