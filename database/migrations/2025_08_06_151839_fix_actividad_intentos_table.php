<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Verificar si la tabla existe
        if (!Schema::hasTable('actividad_intentos')) {
            // Si no existe, crearla con la estructura correcta
            Schema::create('actividad_intentos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
                $table->string('alumno_nombre');
                $table->json('respuestas');
                $table->integer('puntaje')->default(0);
                $table->integer('total_preguntas')->default(0);
                $table->integer('tiempo_completado')->default(0);
                $table->timestamps();
                
                // Índices
                $table->index(['actividad_id', 'alumno_nombre']);
                $table->index('alumno_nombre');
                $table->index('created_at');
            });
            
            return;
        }

        // 2. Si la tabla existe, verificar y corregir columnas
        Schema::table('actividad_intentos', function (Blueprint $table) {
            
            // Asegurar que alumno_nombre existe y es nullable
            if (!Schema::hasColumn('actividad_intentos', 'alumno_nombre')) {
                $table->string('alumno_nombre')->nullable()->after('actividad_id');
            } else {
                // Hacer que alumno_nombre sea nullable si no lo es
                DB::statement('ALTER TABLE actividad_intentos MODIFY alumno_nombre VARCHAR(255) NULL');
            }

            // Asegurar que respuestas existe
            if (!Schema::hasColumn('actividad_intentos', 'respuestas')) {
                $table->json('respuestas')->after('alumno_nombre');
            }

            // Asegurar que puntaje existe
            if (!Schema::hasColumn('actividad_intentos', 'puntaje')) {
                $table->integer('puntaje')->default(0)->after('respuestas');
            }

            // Asegurar que total_preguntas existe
            if (!Schema::hasColumn('actividad_intentos', 'total_preguntas')) {
                $table->integer('total_preguntas')->default(0)->after('puntaje');
            }

            // Asegurar que tiempo_completado existe
            if (!Schema::hasColumn('actividad_intentos', 'tiempo_completado')) {
                $table->integer('tiempo_completado')->default(0)->after('total_preguntas');
            }
        });

        // 3. Eliminar columna alumno_matricula si existe (causa del error)
        if (Schema::hasColumn('actividad_intentos', 'alumno_matricula')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                $table->dropColumn('alumno_matricula');
            });
        }

        // 4. Agregar índices útiles si no existen
        $this->addIndexIfNotExists('actividad_intentos', ['actividad_id', 'alumno_nombre']);
        $this->addIndexIfNotExists('actividad_intentos', ['alumno_nombre']);
        $this->addIndexIfNotExists('actividad_intentos', ['created_at']);
    }

    public function down()
    {
        // En caso de rollback, mantener la estructura básica
        if (Schema::hasTable('actividad_intentos')) {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                // Restaurar alumno_matricula si se necesita para rollback
                if (!Schema::hasColumn('actividad_intentos', 'alumno_matricula')) {
                    $table->string('alumno_matricula')->nullable()->after('alumno_nombre');
                }
            });
        }
    }

    /**
     * Agregar índice solo si no existe
     */
    private function addIndexIfNotExists($table, $columns)
    {
        $indexName = $table . '_' . implode('_', $columns) . '_index';
        
        try {
            Schema::table($table, function ($table) use ($columns, $indexName) {
                $table->index($columns, $indexName);
            });
        } catch (\Exception $e) {
            // El índice ya existe, continuar
        }
    }
};