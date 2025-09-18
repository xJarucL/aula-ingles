<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Modificar tabla actividades
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
                // Agregar grupo_id solo si no existe
                if (!Schema::hasColumn('actividades', 'grupo_id')) {
                    $table->foreignId('grupo_id')->nullable()->after('parcial_id');
                }
                
                // Agregar fecha_disponible solo si no existe
                if (!Schema::hasColumn('actividades', 'fecha_disponible')) {
                    $table->date('fecha_disponible')->nullable()->after('activa');
                }
                
                // Agregar fecha_limite solo si no existe
                if (!Schema::hasColumn('actividades', 'fecha_limite')) {
                    $table->date('fecha_limite')->nullable()->after('fecha_disponible');
                }
                
                // Agregar intentos_permitidos solo si no existe
                if (!Schema::hasColumn('actividades', 'intentos_permitidos')) {
                    $table->integer('intentos_permitidos')->default(3)->after('fecha_limite');
                }
            });

            // Agregar foreign key constraint solo si grupo_id existe y no tiene constraint
            if (Schema::hasColumn('actividades', 'grupo_id')) {
                try {
                    Schema::table('actividades', function (Blueprint $table) {
                        $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe, continuar
                }
            }
        }

        // 2. Modificar tabla actividad_intentos
        if (Schema::hasTable('actividad_intentos')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                // Agregar alumno_id solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                    $table->foreignId('alumno_id')->nullable()->after('actividad_id');
                }
                
                // Agregar numero_intento solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'numero_intento')) {
                    $table->integer('numero_intento')->default(1)->after('alumno_nombre');
                }
                
                // Agregar porcentaje solo si no existe
                if (!Schema::hasColumn('actividad_intentos', 'porcentaje')) {
                    $table->decimal('porcentaje', 5, 2)->nullable()->after('total_preguntas');
                }
            });

            // Hacer alumno_nombre nullable
            try {
                Schema::table('actividad_intentos', function (Blueprint $table) {
                    $table->string('alumno_nombre')->nullable()->change();
                });
            } catch (\Exception $e) {
                // Ya es nullable o hay otro problema, continuar
            }

            // Agregar foreign key constraint solo si alumno_id existe y no tiene constraint
            if (Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                try {
                    Schema::table('actividad_intentos', function (Blueprint $table) {
                        $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
                    });
                } catch (\Exception $e) {
                    // Foreign key ya existe, continuar
                }
            }
        }

        // 3. Crear índices útiles si no existen
        $this->createIndexIfNotExists('actividades', ['grupo_id', 'activa']);
        $this->createIndexIfNotExists('actividades', ['fecha_disponible', 'fecha_limite']);
        $this->createIndexIfNotExists('actividad_intentos', ['alumno_id', 'actividad_id']);
        $this->createIndexIfNotExists('actividad_intentos', ['actividad_id', 'numero_intento']);
    }

    public function down()
    {
        // Eliminar índices
        $this->dropIndexIfExists('actividades', 'actividades_grupo_id_activa_index');
        $this->dropIndexIfExists('actividades', 'actividades_fecha_disponible_fecha_limite_index');
        $this->dropIndexIfExists('actividad_intentos', 'actividad_intentos_alumno_id_actividad_id_index');
        $this->dropIndexIfExists('actividad_intentos', 'actividad_intentos_actividad_id_numero_intento_index');

        // Eliminar foreign keys y columnas de actividades
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
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

        // Eliminar foreign keys y columnas de actividad_intentos
        if (Schema::hasTable('actividad_intentos')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
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
            try {
                Schema::table('actividad_intentos', function (Blueprint $table) {
                    $table->string('alumno_nombre')->nullable(false)->change();
                });
            } catch (\Exception $e) {
                // Ignorar si hay problemas
            }
        }
    }

    /**
     * Crear índice solo si no existe
     */
    private function createIndexIfNotExists($table, $columns)
    {
        try {
            $indexName = $table . '_' . implode('_', $columns) . '_index';
            Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                $table->index($columns, $indexName);
            });
        } catch (\Exception $e) {
            // Índice ya existe, continuar
        }
    }

    /**
     * Eliminar índice solo si existe
     */
    private function dropIndexIfExists($table, $indexName)
    {
        try {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
        } catch (\Exception $e) {
            // Índice no existe, continuar
        }
    }
};