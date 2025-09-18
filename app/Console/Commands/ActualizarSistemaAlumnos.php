<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Alumno;
use App\Models\Cuatrimestre;
use App\Models\Parcial;
use App\Models\ProgresoParcial;
use App\Models\ActividadIntento;
use App\Models\Actividad;

class ActualizarSistemaAlumnos extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'sistema:actualizar-alumnos {--force : Forzar actualización sin confirmación}';

    /**
     * The console command description.
     */
    protected $description = 'Actualizar el sistema de alumnos con las nuevas mejoras de control de parciales';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Iniciando actualización del sistema de alumnos...');

        if (!$this->option('force')) {
            if (!$this->confirm('¿Estás seguro de que quieres actualizar el sistema? Esto puede tomar algunos minutos.')) {
                $this->info('Actualización cancelada.');
                return 0;
            }
        }

        DB::beginTransaction();

        try {
            // Paso 1: Actualizar alumnos con parcial actual
            $this->paso1ActualizarAlumnos();

            // Paso 2: Crear progreso de parciales para alumnos existentes
            $this->paso2CrearProgresoParciales();

            // Paso 3: Calcular y actualizar estadísticas
            $this->paso3CalcularEstadisticas();

            // Paso 4: Validar y corregir matrículas
            $this->paso4ValidarMatriculas();

            // Paso 5: Actualizar actividad_intentos con referencias correctas
            $this->paso5ActualizarIntentos();

            // Paso 6: Crear datos de prueba adicionales si es necesario
            $this->paso6DatosPrueba();

            DB::commit();

            $this->info('✅ ¡Actualización completada exitosamente!');
            $this->mostrarResumen();

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error durante la actualización: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }

    /**
     * Paso 1: Actualizar alumnos con parcial actual
     */
    private function paso1ActualizarAlumnos()
    {
        $this->info('📝 Paso 1: Actualizando alumnos con parcial actual...');

        $alumnos = Alumno::whereNull('parcial_actual')->orWhere('parcial_actual', 0)->get();
        
        $bar = $this->output->createProgressBar($alumnos->count());
        $bar->start();

        foreach ($alumnos as $alumno) {
            // Determinar parcial actual basado en su progreso
            $parcialActual = $this->determinarParcialActual($alumno);
            
            $alumno->update([
                'parcial_actual' => $parcialActual
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Actualizados {$alumnos->count()} alumnos con parcial actual");
    }

    /**
     * Paso 2: Crear progreso de parciales para alumnos existentes
     */
    private function paso2CrearProgresoParciales()
    {
        $this->info('📊 Paso 2: Creando progreso de parciales...');

        $alumnos = Alumno::activos()->get();
        $totalCreados = 0;

        $bar = $this->output->createProgressBar($alumnos->count());
        $bar->start();

        foreach ($alumnos as $alumno) {
            // Buscar cuatrimestre del alumno
            $cuatrimestre = Cuatrimestre::where('orden', $alumno->cuatrimestre_actual)
                                      ->where('activo', true)
                                      ->first();

            if (!$cuatrimestre) {
                $this->warn("No se encontró cuatrimestre activo para alumno {$alumno->matricula}");
                $bar->advance();
                continue;
            }

            // Crear progreso para todos los parciales accesibles
            $parciales = Parcial::where('cuatrimestre_id', $cuatrimestre->id)
                               ->where('numero', '<=', $alumno->parcial_actual)
                               ->get();

            foreach ($parciales as $parcial) {
                $progreso = ProgresoParcial::firstOrCreate([
                    'alumno_id' => $alumno->id,
                    'parcial_id' => $parcial->id
                ], [
                    'cuatrimestre_id' => $cuatrimestre->id,
                    'fecha_inicio' => $alumno->created_at ?? now(),
                    'activo' => $parcial->numero == $alumno->parcial_actual
                ]);

                if ($progreso->wasRecentlyCreated) {
                    $totalCreados++;
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Creados {$totalCreados} registros de progreso de parciales");
    }

    /**
     * Paso 3: Calcular y actualizar estadísticas
     */
    private function paso3CalcularEstadisticas()
    {
        $this->info('📈 Paso 3: Calculando estadísticas de progreso...');

        $progresos = ProgresoParcial::all();
        $bar = $this->output->createProgressBar($progresos->count());
        $bar->start();

        foreach ($progresos as $progreso) {
            $this->calcularEstadisticasProgreso($progreso);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Actualizadas estadísticas para {$progresos->count()} registros de progreso");
    }

    /**
     * Paso 4: Validar y corregir matrículas
     */
    private function paso4ValidarMatriculas()
    {
        $this->info('🔍 Paso 4: Validando formato de matrículas...');

        $alumnosMatriculaInvalida = Alumno::whereRaw("LENGTH(matricula) < 8 OR LENGTH(matricula) > 10")->get();
        $corregidas = 0;

        if ($alumnosMatriculaInvalida->count() > 0) {
            $this->warn("Encontradas {$alumnosMatriculaInvalida->count()} matrículas con formato inválido");

            foreach ($alumnosMatriculaInvalida as $alumno) {
                $matriculaCorregida = $this->corregirMatricula($alumno->matricula);
                
                if ($matriculaCorregida) {
                    $alumno->update(['matricula' => $matriculaCorregida]);
                    $corregidas++;
                    $this->line("Corregida: {$alumno->matricula} -> {$matriculaCorregida}");
                } else {
                    $this->warn("No se pudo corregir matrícula: {$alumno->matricula} (ID: {$alumno->id})");
                }
            }
        }

        $this->info("✅ Corregidas {$corregidas} matrículas");
    }

    /**
     * Paso 5: Actualizar actividad_intentos con referencias correctas
     */
    private function paso5ActualizarIntentos()
    {
        $this->info('🎯 Paso 5: Actualizando referencias de intentos...');

        // Actualizar intentos que solo tienen nombre pero no alumno_id
        $intentosSinAlumnoId = ActividadIntento::whereNull('alumno_id')
                                             ->whereNotNull('alumno_nombre')
                                             ->get();

        $actualizados = 0;
        $bar = $this->output->createProgressBar($intentosSinAlumnoId->count());
        $bar->start();

        foreach ($intentosSinAlumnoId as $intento) {
            $alumno = $this->buscarAlumnoPorNombre($intento->alumno_nombre);
            
            if ($alumno) {
                $intento->update(['alumno_id' => $alumno->id]);
                $actualizados++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✅ Actualizados {$actualizados} intentos con referencia de alumno");
    }

    /**
     * Paso 6: Crear datos de prueba adicionales si es necesario
     */
    private function paso6DatosPrueba()
    {
        if (!app()->environment('production')) {
            $this->info('🧪 Paso 6: Creando datos de prueba adicionales...');

            // Crear algunos alumnos de prueba con diferentes estados
            $this->crearAlumnosPrueba();
            $this->info("✅ Datos de prueba creados");
        } else {
            $this->info('⏭️  Paso 6: Saltado (entorno de producción)');
        }
    }

    /**
     * Determinar el parcial actual de un alumno basado en su progreso
     */
    private function determinarParcialActual(Alumno $alumno): int
    {
        // Buscar el último intento del alumno
        $ultimoIntento = ActividadIntento::where(function($query) use ($alumno) {
                $query->where('alumno_id', $alumno->id)
                      ->orWhere('alumno_nombre', $alumno->nombre_completo);
            })
            ->with('actividad.parcial')
            ->latest()
            ->first();

        if ($ultimoIntento && $ultimoIntento->actividad) {
            return $ultimoIntento->actividad->parcial->numero;
        }

        // Si no hay intentos, asumir primer parcial
        return 1;
    }

    /**
     * Calcular estadísticas de un progreso específico
     */
    private function calcularEstadisticasProgreso(ProgresoParcial $progreso)
    {
        // Contar actividades del parcial
        $totalActividades = Actividad::where('parcial_id', $progreso->parcial_id)
                                   ->where('activa', true)
                                   ->count();

        // Contar actividades completadas por el alumno
        $actividadesCompletadas = ActividadIntento::where('alumno_id', $progreso->alumno_id)
            ->whereHas('actividad', function($query) use ($progreso) {
                $query->where('parcial_id', $progreso->parcial_id);
            })
            ->distinct('actividad_id')
            ->count();

        // Calcular promedio de calificaciones
        $promedioCalificaciones = ActividadIntento::where('alumno_id', $progreso->alumno_id)
            ->whereHas('actividad', function($query) use ($progreso) {
                $query->where('parcial_id', $progreso->parcial_id);
            })
            ->avg('porcentaje');

        // Determinar si está completado
        $fechaCompletado = null;
        if ($actividadesCompletadas >= $totalActividades && $totalActividades > 0) {
            $ultimoIntento = ActividadIntento::where('alumno_id', $progreso->alumno_id)
                ->whereHas('actividad', function($query) use ($progreso) {
                    $query->where('parcial_id', $progreso->parcial_id);
                })
                ->latest()
                ->first();
            
            $fechaCompletado = $ultimoIntento ? $ultimoIntento->created_at : now();
        }

        $progreso->update([
            'total_actividades' => $totalActividades,
            'actividades_completadas' => $actividadesCompletadas,
            'promedio_calificaciones' => $promedioCalificaciones,
            'fecha_completado' => $fechaCompletado
        ]);
    }

    /**
     * Corregir formato de matrícula
     */
    private function corregirMatricula(string $matricula): ?string
    {
        // Remover espacios y caracteres no numéricos
        $matriculaLimpia = preg_replace('/[^0-9]/', '', $matricula);

        // Si tiene menos de 8 dígitos, intentar agregar año actual
        if (strlen($matriculaLimpia) < 8) {
            $year = date('Y');
            $matriculaLimpia = $year . str_pad($matriculaLimpia, 6, '0', STR_PAD_LEFT);
        }

        // Validar que tenga el formato correcto
        if (Alumno::matriculaValida($matriculaLimpia)) {
            return $matriculaLimpia;
        }

        return null;
    }

    /**
     * Buscar alumno por nombre
     */
    private function buscarAlumnoPorNombre(string $nombreCompleto): ?Alumno
    {
        // Buscar por nombre completo exacto
        $alumno = Alumno::whereRaw("CONCAT(nombre, ' ', apellidos) = ?", [$nombreCompleto])->first();

        if (!$alumno) {
            // Buscar por similitud en el nombre
            $alumno = Alumno::whereRaw("CONCAT(nombre, ' ', apellidos) LIKE ?", ["%{$nombreCompleto}%"])->first();
        }

        return $alumno;
    }

    /**
     * Crear alumnos de prueba
     */
    private function crearAlumnosPrueba()
    {
        $carreraIds = \App\Models\Carrera::pluck('id')->toArray();
        
        if (empty($carreraIds)) {
            $this->warn('No hay carreras disponibles para crear alumnos de prueba');
            return;
        }

        $alumnosPrueba = [
            [
                'nombre' => 'Test Primer',
                'apellidos' => 'Parcial Uno',
                'cuatrimestre' => 1,
                'parcial' => 1
            ],
            [
                'nombre' => 'Test Segundo', 
                'apellidos' => 'Parcial Dos',
                'cuatrimestre' => 1,
                'parcial' => 2
            ],
            [
                'nombre' => 'Test Avanzado',
                'apellidos' => 'Cuatrimestre Dos',
                'cuatrimestre' => 2,
                'parcial' => 1
            ]
        ];

        foreach ($alumnosPrueba as $datos) {
            $matricula = Alumno::generarMatricula();
            
            Alumno::updateOrCreate(
                ['matricula' => $matricula],
                [
                    'nombre' => $datos['nombre'],
                    'apellidos' => $datos['apellidos'],
                    'carrera_id' => $carreraIds[array_rand($carreraIds)],
                    'cuatrimestre_actual' => $datos['cuatrimestre'],
                    'parcial_actual' => $datos['parcial'],
                    'email' => strtolower($matricula) . '@test.utesc.edu.mx',
                    'activo' => true
                ]
            );
        }
    }

    /**
     * Mostrar resumen de la actualización
     */
    private function mostrarResumen()
    {
        $this->newLine();
        $this->info('📋 RESUMEN DE ACTUALIZACIÓN:');
        $this->line('─────────────────────────────');

        $totalAlumnos = Alumno::count();
        $alumnosActivos = Alumno::activos()->count();
        $totalProgreso = ProgresoParcial::count();
        $totalIntentos = ActividadIntento::count();

        $this->line("👥 Total de alumnos: {$totalAlumnos}");
        $this->line("✅ Alumnos activos: {$alumnosActivos}");
        $this->line("📊 Registros de progreso: {$totalProgreso}");
        $this->line("🎯 Total de intentos: {$totalIntentos}");

        $this->newLine();
        $this->info('🎉 ¡El sistema ha sido actualizado exitosamente!');
        $this->info('Ahora los alumnos tienen control estricto por cuatrimestre y parcial.');
        
        $this->newLine();
        $this->line('Próximos pasos recomendados:');
        $this->line('1. Ejecutar: php artisan config:cache');
        $this->line('2. Ejecutar: php artisan route:cache');
        $this->line('3. Probar el acceso de alumnos');
        $this->line('4. Verificar que los middlewares estén registrados');
    }
}

/**
 * Seeder específico para actualizar datos existentes
 */
class ActualizarDatosExistentesSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        // Llamar al comando de actualización
        \Artisan::call('sistema:actualizar-alumnos', ['--force' => true]);
        
        echo "Datos actualizados exitosamente.\n";
    }
}