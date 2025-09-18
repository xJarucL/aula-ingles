<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


// === MIGRACIÓN 2: PERIODOS ESCOLARES (REEMPLAZA periodos_academicos) ===
// Archivo: 2025_01_01_000002_create_periodos_escolares_table.php

return new class extends Migration
{
    public function up()
    {
        Schema::create('periodos_escolares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60); // "Cuatrimestre Ene-Abr 2025"
            $table->string('codigo', 20)->unique(); // "2025-1"
            $table->enum('tipo', ['academico', 'estadias']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(false); // Solo uno activo a la vez
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodos_escolares');
    }
};
