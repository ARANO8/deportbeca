<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('rol_modulo_permiso', function (Blueprint $table) {
            if (! Schema::hasColumn('rol_modulo_permiso', 'modulo')) {
                $table->string('modulo', 50)->after('rol_id');
            }
        });
    }

    public function down()
    {
        Schema::table('rol_modulo_permiso', function (Blueprint $table) {
            $table->dropColumn('modulo');
        });
    }
};
