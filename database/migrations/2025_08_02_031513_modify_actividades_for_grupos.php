<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Solo agregar campos que NO existan ya en actividad_intentos
        Schema::table('actividad_intentos', function (Blueprint $table) {
            // Verificar si alumno_id NO existe antes de agregarlo
            if (!Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                $table->foreignId('alumno_id')->nullable()->after('actividad_id')->constrained('alumnos')->onDelete('cascade');
            }
            
            // Verificar si numero_intento NO existe antes de agregarlo
            if (!Schema::hasColumn('actividad_intentos', 'numero_intento')) {
                $table->integer('numero_intento')->default(1)->after('alumno_nombre');
            }
            
            // Verificar si porcentaje NO existe antes de agregarlo
            if (!Schema::hasColumn('actividad_intentos', 'porcentaje')) {
                $table->decimal('porcentaje', 5, 2)->nullable()->after('total_preguntas');
            }
        });

        // Hacer que alumno_nombre sea nullable si no lo es ya
        $columns = Schema::getColumnListing('actividad_intentos');
        $columnDetails = collect(\DB::select("DESCRIBE actividad_intentos"))->keyBy('Field');
        
        if (isset($columnDetails['alumno_nombre']) && $columnDetails['alumno_nombre']->Null === 'NO') {
            Schema::table('actividad_intentos', function (Blueprint $table) {
                $table->string('alumno_nombre')->nullable()->change();
            });
        }

        // Agregar índices solo si no existen
        $this->addIndexIfNotExists('actividad_intentos', ['alumno_id', 'actividad_id'], 'actividad_intentos_alumno_actividad_index');
        $this->addIndexIfNotExists('actividad_intentos', ['actividad_id', 'numero_intento'], 'actividad_intentos_actividad_numero_index');
    }

    public function down()
    {
        Schema::table('actividad_intentos', function (Blueprint $table) {
            // Solo eliminar si existen
            if (Schema::hasColumn('actividad_intentos', 'alumno_id')) {
                $table->dropForeign(['alumno_id']);
                $table->dropColumn(['alumno_id']);
            }
            
            if (Schema::hasColumn('actividad_intentos', 'numero_intento')) {
                $table->dropColumn(['numero_intento']);
            }
            
            if (Schema::hasColumn('actividad_intentos', 'porcentaje')) {
                $table->dropColumn(['porcentaje']);
            }
            
            // Revertir alumno_nombre a no nullable
            $table->string('alumno_nombre')->nullable(false)->change();
        });
    }

    /**
     * Agregar índice solo si no existe
     */
    private function addIndexIfNotExists($table, $columns, $indexName)
    {
        $indexExists = collect(\DB::select("SHOW INDEX FROM {$table} WHERE Key_name = '{$indexName}'"))->isNotEmpty();
        
        if (!$indexExists) {
            Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                $table->index($columns, $indexName);
            });
        }
    }
};