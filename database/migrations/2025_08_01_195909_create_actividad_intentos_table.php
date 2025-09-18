<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('actividad_intentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actividad_id')->constrained('actividades')->onDelete('cascade');
            $table->string('alumno_nombre');
            $table->json('respuestas');
            $table->integer('puntaje')->default(0);
            $table->integer('total_preguntas')->default(0);
            $table->integer('tiempo_completado')->default(0); // en segundos
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index(['actividad_id', 'alumno_nombre']);
            $table->index('alumno_nombre');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actividad_intentos');
    }
};