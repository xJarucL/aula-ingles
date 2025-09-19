<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parciales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuatrimestre_id')->constrained('cuatrimestres')->onDelete('cascade');
            $table->string('nombre'); // Ejemplo: "Primer Parcial"
            $table->unsignedTinyInteger('numero'); // 1, 2, 3
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('parciales');
    }
};
