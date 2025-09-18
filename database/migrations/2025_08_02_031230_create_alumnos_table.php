<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('matricula', 20)->unique();
            $table->string('email', 100)->unique()->nullable();
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->integer('cuatrimestre_actual')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamp('ultimo_acceso')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['carrera_id', 'cuatrimestre_actual']);
            $table->index('matricula');
            $table->index('activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
};