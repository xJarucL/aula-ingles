<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Modificar tabla actividades solo si no existen las columnas
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
                // Verificar y agregar grupo_id solo si no existe
                if (!Schema::hasColumn('actividades', 'grupo_id')) {
                    $table->foreignId('grupo_id')->nullable()->after('parcial_id')->constrained('grupos')->onDelete('cascade');
                }
                
                // Verificar y agregar fecha_disponible solo si no existe
                if (!Schema::hasColumn('actividades', 'fecha_disponible')) {
                    $table->date('fecha_disponible')->nullable()->after('activa');
                }
                
                // Verificar y agregar fecha_limite solo si no existe
                if (!Schema::hasColumn('actividades', 'fecha_limite')) {
                    $table->date('fecha_limite')->nullable()->after('fecha_disponible');
                }
                
                // Verificar y agregar intentos_permitidos solo si no existe
                if (!Schema::hasColumn('actividades', 'intentos_permitidos')) {
                    $table->integer('intentos_permitidos')->default(3)->after('fecha_limite');
                }
            });

            // Agregar índices solo si no existen
            $this->addIndexIfNotExists('actividades', ['grupo_id', 'activa'], 'actividades_grupo_activa_index');
            $this->addIndexIfNotExists('actividades', ['fecha_disponible', 'fecha_limite'], 'actividades_fechas_index');
        }

        // Modificar tabla actividad_intentos solo si no existen las columnas
        if (Schema::hasTable('actividad_intentos')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                // Verificar y agregar alumno_id solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                    $table->foreignId('alumno_id')->nullable()->after('actividad_id')->constrained('alumnos')->onDelete('cascade');
                }
                
                // Verificar y agregar numero_intento solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'numero_intento')) {
                    $table->integer('numero_intento')->default(1)->after('alumno_nombre');
                }
                
                // Verificar y agregar porcentaje solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'porcentaje')) {
                    $table->decimal('porcentaje', 5, 2)->nullable()->after('total_preguntas');
                }
            });

            // Hacer alumno_nombre nullable si no lo es
            if (Schema::hasColumn('actividad_intentos', 'alumno_nombre')) {
                $columnType = DB::select("SHOW COLUMNS FROM actividad_intentos WHERE Field = 'alumno_nombre'")[0];
                if (stripos($columnType->Null, 'NO') !== false) {
                    Schema::table('actividad_intentos', function (Blueprint $table) {
                        $table->string('alumno_nombre')->nullable()->change();
                    });
                }
            }

            // Agregar índices solo si no existen
            $this->addIndexIfNotExists('actividad_intentos', ['alumno_id', 'actividad_id'], 'intentos_alumno_actividad_index');
            $this->addIndexIfNotExists('actividad_intentos', ['actividad_id', 'numero_intento'], 'intentos_actividad_numero_index');
        }
    }

    public function down()
    {
        // Eliminar cambios en actividades
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
                // Eliminar índices
                $this->dropIndexIfExists('actividades', 'actividades_grupo_activa_index');
                $this->dropIndexIfExists('actividades', 'actividades_fechas_index');
                
                // Eliminar columnas si existen
                if (Schema::hasColumn('actividades', 'grupo_id')) {
                    $table->dropForeign(['grupo_id']);
                    $table->dropColumn('grupo_id');
                }
                if (Schema::hasColumn('actividades', 'fecha_disponible')) {
                    $table->dropColumn('fecha_disponible');
                }
                if (Schema::hasColumn('actividades', 'fecha_limite')) {
                    $table->dropColumn('fecha_limite');
                }
                if (Schema::hasColumn('actividades', 'intentos_permitidos')) {
                    $table->dropColumn('intentos_permitidos');
                }
            });
        }

        // Eliminar cambios en actividad_intentos
        if (Schema::hasTable('actividad_intentos')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                // Eliminar índices
                $this->dropIndexIfExists('actividad_intentos', 'intentos_alumno_actividad_index');
                $this->dropIndexIfExists('actividad_intentos', 'intentos_actividad_numero_index');
                
                // Eliminar columnas si existen
                if (Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                    $table->dropForeign(['alumno_id']);
                    $table->dropColumn('alumno_id');
                }
                if (Schema::hasColumn('actividad_intentos', 'numero_intento')) {
                    $table->dropColumn('numero_intento');
                }
                if (Schema::hasColumn('actividad_intentos', 'porcentaje')) {
                    $table->dropColumn('porcentaje');
                }
            });

            // Restaurar alumno_nombre como NOT NULL
            if (Schema::hasColumn('actividad_intentos', 'alumno_nombre')) {
                Schema::table('actividad_intentos', function (Blueprint $table) {
                    $table->string('alumno_nombre')->nullable(false)->change();
                });
            }
        }
    }

    /**
     * Agregar índice solo si no existe
     */
    private function addIndexIfNotExists($table, $columns, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        $existingIndexes = collect($indexes)->pluck('Key_name')->toArray();
        
        if (!in_array($indexName, $existingIndexes)) {
            Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                $table->index($columns, $indexName);
            });
        }
    }

    /**
     * Eliminar índice solo si existe
     */
    private function dropIndexIfExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        $existingIndexes = collect($indexes)->pluck('Key_name')->toArray();
        
        if (in_array($indexName, $existingIndexes)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        }
    }
};