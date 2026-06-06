<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega el flag es_super_admin a roles.
 *
 * Reemplaza la comparacion fragil por nombre ('administrador') en los
 * middleware de autorizacion. Un rol marcado como super admin tiene acceso
 * irrestricto a todos los modulos sin depender de su nombre, de modo que
 * renombrar el rol no rompe la autorizacion.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('es_super_admin')->default(false)->after('descripcion');
        });

        // El rol Administrador existente pasa a ser super admin
        DB::table('roles')
            ->whereRaw('LOWER(nombre) = ?', ['administrador'])
            ->update(['es_super_admin' => true]);
    }

    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('es_super_admin');
        });
    }
};
