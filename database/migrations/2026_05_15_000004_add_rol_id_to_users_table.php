<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * NOTA: Este archivo fue nombrado incorrectamente — su contenido real
 * crea la tabla rol_modulo_permiso (versión inicial sin FK a roles).
 * La columna rol_id en users la agrega la migración
 * 2026_06_05_000001_add_rol_id_to_users_table.php
 */
return new class extends Migration
{
    public function up()
    {
        // Solo crea si no existe; la migración correcta (051359) la recreará
        // con foreign key y unique constraint.
        if (! Schema::hasTable('rol_modulo_permiso')) {
            Schema::create('rol_modulo_permiso', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rol_id');
                $table->string('modulo', 50);
                $table->boolean('ver')->default(false);
                $table->boolean('crear')->default(false);
                $table->boolean('editar')->default(false);
                $table->boolean('eliminar')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // La migración 051359 es quien realmente la gestiona; no hacer nada aquí.
    }
};
