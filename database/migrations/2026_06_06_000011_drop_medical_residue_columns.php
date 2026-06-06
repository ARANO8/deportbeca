<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina columnas residuo del sistema medico anterior.
 *
 * - users.address: direccion del consultorio en el sistema medico original.
 *   Nunca se muestra ni edita en el sistema deportivo.
 * - paginas.speciali: "especialidad del medico". La tabla paginas se reutilizo
 *   para los comunicados del landing; esta columna quedo sin uso.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('address');
            });
        }

        if (Schema::hasColumn('paginas', 'speciali')) {
            Schema::table('paginas', function (Blueprint $table) {
                $table->dropColumn('speciali');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'address')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('address')->nullable()->after('carnet');
            });
        }

        if (! Schema::hasColumn('paginas', 'speciali')) {
            Schema::table('paginas', function (Blueprint $table) {
                $table->string('speciali')->nullable()->after('nombre');
            });
        }
    }
};
