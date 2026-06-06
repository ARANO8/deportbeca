<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina la tabla permisos (Sistema B de permisos atomicos).
 *
 * Nunca se uso: el sistema de autorizacion vigente es rol_modulo_permiso
 * (Sistema A, matriz de modulos con permisos CRUD). La tabla rol_permiso
 * asociada nunca llego a crearse en este proyecto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('permisos');
    }

    public function down(): void
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->string('clave', 50)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->timestamps();
        });
    }
};
