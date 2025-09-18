<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. ALUMNOS: Agregar parcial_actual si no existe
        if (!Schema::hasColumn('alumnos', 'parcial_actual')) {
            Schema::table('alumnos', function (Blueprint $table) {
                $table->tinyInteger('parcial_actual')->default(1)->after('cuatrimestre_actual');
            });
            
            // Actualizar alumnos existentes
            DB::table('alumnos')->whereNull('parcial_actual')->update(['parcial_actual' => 1]);
        }

        // 2. ACTIVIDADES: Agregar campos de control de parcial
        $actividadesNecesita = [];
        if (!Schema::hasColumn('actividades', 'fecha_inicio_parcial')) {
            $actividadesNecesita[] = 'fecha_inicio_parcial';
        }
        if (!Schema::hasColumn('actividades', 'fecha_fin_parcial')) {
            $actividadesNecesita[] = 'fecha_fin_parcial';
        }

        if (!empty($actividadesNecesita)) {
            Schema::table('actividades', function (Blueprint $table) use ($actividadesNecesita) {
                if (in_array('fecha_inicio_parcial', $actividadesNecesita)) {
                    $table->date('fecha_inicio_parcial')->nullable()->after('fecha_disponible');
                }
                if (in_array('fecha_fin_parcial', $actividadesNecesita)) {
                    $table->date('fecha_fin_parcial')->nullable()->after('fecha_inicio_parcial');
                }
            });
        }

        // 3. GRUPOS: Agregar cuatrimestre_objetivo
        if (!Schema::hasColumn('grupos', 'cuatrimestre_objetivo')) {
            Schema::table('grupos', function (Blueprint $table) {
                $table->integer('cuatrimestre_objetivo')->default(1)->after('periodo_escolar_id');
            });
        }

        // 4. INSCRIPCIONES: Agregar campos de control
        $inscripcionesNecesita = [];
        if (!Schema::hasColumn('inscripciones', 'periodo_escolar_id')) {
            $inscripcionesNecesita[] = 'periodo_escolar_id';
        }
        if (!Schema::hasColumn('inscripciones', 'cuatrimestre_inscripcion')) {
            $inscripcionesNecesita[] = 'cuatrimestre_inscripcion';
        }
        if (!Schema::hasColumn('inscripciones', 'parcial_inscripcion')) {
            $inscripcionesNecesita[] = 'parcial_inscripcion';
        }

        if (!empty($inscripcionesNecesita)) {
            Schema::table('inscripciones', function (Blueprint $table) use ($inscripcionesNecesita) {
                if (in_array('periodo_escolar_id', $inscripcionesNecesita)) {
                    $table->bigInteger('periodo_escolar_id')->unsigned()->nullable()->after('grupo_id');
                }
                if (in_array('cuatrimestre_inscripcion', $inscripcionesNecesita)) {
                    $table->integer('cuatrimestre_inscripcion')->default(1)->after('fecha_inscripcion');
                }
                if (in_array('parcial_inscripcion', $inscripcionesNecesita)) {
                    $table->tinyInteger('parcial_inscripcion')->default(1)->after('cuatrimestre_inscripcion');
                }
            });
        }

        // 5. VERIFICAR PROGRESO_PARCIALES (ya debería existir)
        if (!Schema::hasTable('progreso_parciales')) {
            Schema::create('progreso_parciales', function (Blueprint $table) {
                $table->id();
                $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
                $table->foreignId('cuatrimestre_id')->constrained('cuatrimestres')->onDelete('cascade');
                $table->foreignId('parcial_id')->constrained('parciales')->onDelete('cascade');
                $table->integer('actividades_completadas')->default(0);
                $table->integer('total_actividades')->default(0);
                $table->decimal('promedio_calificaciones', 5, 2)->nullable();
                $table->date('fecha_inicio')->nullable();
                $table->date('fecha_completado')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();

                $table->unique(['alumno_id', 'parcial_id'], 'progreso_alumno_parcial');
                $table->index(['cuatrimestre_id', 'parcial_id'], 'progreso_cuatrimestre_parcial');
                $table->index(['alumno_id', 'activo'], 'progreso_alumno_activo');
            });
        }

        // 6. CREAR/ACTUALIZAR VISTA (CORREGIDA para usar 'activa' en lugar de 'activo')
        DB::statement("DROP VIEW IF EXISTS vista_acceso_alumnos");
        DB::statement("
            CREATE VIEW vista_acceso_alumnos AS
            SELECT 
                a.id as alumno_id,
                a.nombre,
                a.apellidos,
                a.matricula,
                a.cuatrimestre_actual,
                a.parcial_actual,
                a.carrera_id,
                c.nombre as carrera_nombre,
                cuat.id as cuatrimestre_id,
                cuat.nombre as cuatrimestre_nombre,
                p.id as parcial_id,
                p.nombre as parcial_nombre,
                p.numero as parcial_numero
            FROM alumnos a
            JOIN carreras c ON a.carrera_id = c.id AND c.activa = 1
            JOIN cuatrimestres cuat ON cuat.orden = a.cuatrimestre_actual AND cuat.activo = 1
            JOIN parciales p ON p.cuatrimestre_id = cuat.id AND p.numero = a.parcial_actual
            WHERE a.activo = 1
        ");

        // 7. AGREGAR ÍNDICES ÚTILES
        $this->crearIndicesUtiles();

        // 8. ACTUALIZAR DATOS
        $this->actualizarDatos();
    }

    /**
     * Crear índices útiles
     */
    private function crearIndicesUtiles()
    {
        $indices = [
            ['tabla' => 'alumnos', 'columnas' => ['cuatrimestre_actual', 'carrera_id', 'activo'], 'nombre' => 'idx_alumno_cuatrimestre_carrera'],
            ['tabla' => 'actividades', 'columnas' => ['parcial_id', 'activa', 'fecha_disponible'], 'nombre' => 'idx_parcial_activa_fecha'],
            ['tabla' => 'actividad_intentos', 'columnas' => ['alumno_id', 'actividad_id', 'numero_intento'], 'nombre' => 'idx_alumno_actividad_numero'],
        ];

        foreach ($indices as $indice) {
            if (!$this->indiceExiste($indice['tabla'], $indice['nombre'])) {
                try {
                    Schema::table($indice['tabla'], function (Blueprint $table) use ($indice) {
                        $table->index($indice['columnas'], $indice['nombre']);
                    });
                } catch (\Exception $e) {
                    // Ignorar si ya existe o hay problemas
                }
            }
        }
    }

    /**
     * Verificar si un índice existe
     */
    private function indiceExiste($tabla, $nombreIndice)
    {
        try {
            $indices = DB::select("SHOW INDEX FROM {$tabla} WHERE Key_name = '{$nombreIndice}'");
            return !empty($indices);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Actualizar datos existentes
     */
    private function actualizarDatos()
    {
        // Actualizar parcial_actual en alumnos
        if (Schema::hasColumn('alumnos', 'parcial_actual')) {
            DB::table('alumnos')
                ->whereNull('parcial_actual')
                ->orWhere('parcial_actual', 0)
                ->update(['parcial_actual' => 1]);
        }

        // Actualizar numero_intento en actividad_intentos
        if (Schema::hasColumn('actividad_intentos', 'numero_intento')) {
            DB::table('actividad_intentos')
                ->whereNull('numero_intento')
                ->orWhere('numero_intento', 0)
                ->update(['numero_intento' => 1]);
        }

        // Calcular porcentajes faltantes
        if (Schema::hasColumn('actividad_intentos', 'porcentaje')) {
            DB::statement("
                UPDATE actividad_intentos 
                SET porcentaje = CASE 
                    WHEN total_preguntas > 0 THEN ROUND((puntaje / total_preguntas) * 100, 2)
                    ELSE 0 
                END
                WHERE porcentaje IS NULL OR porcentaje = 0
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar vista
        DB::statement("DROP VIEW IF EXISTS vista_acceso_alumnos");

        // Revertir cambios en tablas (solo si existen)
        $cambios = [
            'inscripciones' => ['periodo_escolar_id', 'cuatrimestre_inscripcion', 'parcial_inscripcion'],
            'grupos' => ['cuatrimestre_objetivo'],
            'actividades' => ['fecha_inicio_parcial', 'fecha_fin_parcial'],
            'alumnos' => ['parcial_actual']
        ];

        foreach ($cambios as $tabla => $columnas) {
            if (Schema::hasTable($tabla)) {
                Schema::table($tabla, function (Blueprint $table) use ($columnas) {
                    foreach ($columnas as $columna) {
                        if (Schema::hasColumn($table->getTable(), $columna)) {
                            $table->dropColumn($columna);
                        }
                    }
                });
            }
        }
    }
};