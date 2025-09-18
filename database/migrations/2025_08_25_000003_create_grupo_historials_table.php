<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrupoHistorialsTable extends Migration
{
    public function up()
    {
        Schema::create('grupo_historials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('grupo_id');
            $table->unsignedBigInteger('cuatrimestre_id')->nullable();
            $table->unsignedBigInteger('periodo_escolar_id')->nullable();
            $table->json('snapshot')->nullable();
            $table->unsignedBigInteger('promovido_por')->nullable();
            $table->timestamps();

            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grupo_historials');
    }
}
