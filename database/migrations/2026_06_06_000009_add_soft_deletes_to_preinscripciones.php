<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega borrado logico (soft deletes) a preinscripciones.
 *
 * Una preinscripcion es un registro de un atleta para un evento: borrarla
 * permanentemente destruye historial y documentacion. Con soft deletes se
 * conserva el dato y se puede auditar / restaurar, en linea con series y
 * partidos que ya usan SoftDeletes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
