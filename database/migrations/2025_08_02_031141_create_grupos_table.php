<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50); // "TIDS-101", "CONT-201", etc.
            $table->string('codigo', 20)->unique(); // Código único del grupo
            $table->foreignId('materia_id')->constrained('materias')->onDelete('cascade');
            $table->foreignId('periodo_escolar_id')->constrained('periodos_escolares')->onDelete('cascade');
            $table->foreignId('profesor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->integer('capacidad_maxima')->default(30);
            $table->boolean('activo')->default(true);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['materia_id', 'periodo_escolar_id']);
            $table->index('profesor_id');
            $table->index('activo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupos');
    }
};