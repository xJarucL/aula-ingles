<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrupoIdToActividadesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
                if (!Schema::hasColumn('actividades', 'grupo_id')) {
                    $table->unsignedBigInteger('grupo_id')->nullable()->after('id');
                    $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('set null');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('actividades')) {
            Schema::table('actividades', function (Blueprint $table) {
                if (Schema::hasColumn('actividades', 'grupo_id')) {
                    $table->dropForeign(['grupo_id']);
                    $table->dropColumn('grupo_id');
                }
            });
        }
    }
}
