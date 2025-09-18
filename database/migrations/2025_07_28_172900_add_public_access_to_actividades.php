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
        Schema::table('actividades', function (Blueprint $table) {
            // Campos para acceso público
            $table->string('link_publico', 100)->unique()->nullable()->after('activa');
            $table->boolean('acceso_publico')->default(false)->after('link_publico');
            $table->timestamp('fecha_limite')->nullable()->after('acceso_publico');
            
            // Índice para el link público
            $table->index('link_publico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actividades', function (Blueprint $table) {
            $table->dropIndex(['link_publico']);
            $table->dropColumn(['link_publico', 'acceso_publico', 'fecha_limite']);
        });
    }
};