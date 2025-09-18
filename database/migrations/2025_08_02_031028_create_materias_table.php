<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // "Inglés I", "Inglés II", etc.
            $table->string('codigo', 20)->unique(); // "ING-I", "ING-II"
            $table->text('descripcion')->nullable();
            $table->foreignId('carrera_id')->constrained('carreras')->onDelete('cascade');
            $table->integer('cuatrimestre_numero'); // 1, 2, 3, etc.
            $table->boolean('activa')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['carrera_id', 'cuatrimestre_numero']);
            $table->index('activa');
        });
    }

    public function down()
    {
        Schema::dropIfExists('materias');
    }
};

