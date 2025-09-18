<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('cascade');
            $table->foreignId('grupo_id')->constrained('grupos')->onDelete('cascade');
            $table->enum('estado', ['inscrito', 'retirado', 'completado'])->default('inscrito');
            $table->decimal('calificacion_final', 5, 2)->nullable();
            $table->timestamp('fecha_inscripcion');
            $table->timestamp('fecha_retiro')->nullable();
            $table->timestamps();
            
            // Un alumno no puede estar inscrito dos veces en el mismo grupo
            $table->unique(['alumno_id', 'grupo_id']);
            
            // Índices
            $table->index(['grupo_id', 'estado']);
            $table->index('alumno_id');
            $table->index('fecha_inscripcion');
        });
    }

    public function down()
    {
        Schema::dropIfExists('inscripciones');
    }
};