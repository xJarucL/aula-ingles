<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations - Solo agregar campo faltante
     */
    public function up(): void
    {
        // SOLO agregar cuatrimestre_id si no existe
        if (!Schema::hasColumn('alumnos', 'cuatrimestre_id')) {
            Schema::table('alumnos', function (Blueprint $table) {
                $table->unsignedBigInteger('cuatrimestre_id')->nullable()->after('carrera_id');
                $table->index('cuatrimestre_id');
            });
            
            // Asignar valor por defecto a registros existentes
            DB::table('alumnos')->whereNull('cuatrimestre_id')->update(['cuatrimestre_id' => 1]);
            echo "✅ Campo cuatrimestre_id agregado\n";
        }
        
        // Agregar activo si no existe
        if (!Schema::hasColumn('alumnos', 'activo')) {
            Schema::table('alumnos', function (Blueprint $table) {
                $table->boolean('activo')->default(true);
            });
            echo "✅ Campo activo agregado\n";
        }
        
        // Agregar apellidos si no existe  
        if (!Schema::hasColumn('alumnos', 'apellidos')) {
            Schema::table('alumnos', function (Blueprint $table) {
                $table->string('apellidos')->nullable()->after('nombre');
            });
            echo "✅ Campo apellidos agregado\n";
        }
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('alumnos', function (Blueprint $table) {
            if (Schema::hasColumn('alumnos', 'cuatrimestre_id')) {
                $table->dropColumn('cuatrimestre_id');
            }
            if (Schema::hasColumn('alumnos', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('alumnos', 'apellidos')) {
                $table->dropColumn('apellidos');
            }
        });
    }
};