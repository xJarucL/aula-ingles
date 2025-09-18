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
        Schema::create('mensajes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('de_usuario_id'); // Emisor
            $table->unsignedBigInteger('para_usuario_id')->nullable(); // Receptor (null = chat grupal)

            $table->text('mensaje');

            $table->timestamps();

            // Relaciones
            $table->foreign('de_usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('para_usuario_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
