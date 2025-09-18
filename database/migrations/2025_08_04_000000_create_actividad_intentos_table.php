<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('actividad_intentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->string('alumno_nombre'); // Para mantener compatibilidad
            $table->json('respuestas');
            $table->integer('puntaje');
            $table->integer('total_preguntas');
            $table->decimal('porcentaje', 5, 2);
            $table->integer('numero_intento');
            $table->integer('tiempo_completado')->default(0);
            $table->timestamps();

            // Índices para mejorar rendimiento
            $table->index(['actividad_id', 'alumno_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividad_intentos');
    }
};
