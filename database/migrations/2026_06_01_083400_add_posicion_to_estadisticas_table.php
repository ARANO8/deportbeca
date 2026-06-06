<?php

// database/migrations/xxxx_add_posicion_to_estadisticas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('estadisticas', function (Blueprint $table) {
            $table->integer('posicion_final')->nullable()->after('pts');
            $table->integer('tiempo')->nullable()->after('posicion_final');
            $table->string('marca', 50)->nullable()->after('tiempo');
        });
    }

    public function down()
    {
        Schema::table('estadisticas', function (Blueprint $table) {
            $table->dropColumn(['posicion_final', 'tiempo', 'marca']);
        });
    }
};
