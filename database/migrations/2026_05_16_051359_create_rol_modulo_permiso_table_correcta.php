<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('rol_modulo_permiso');
        Schema::create('rol_modulo_permiso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
            $table->string('modulo', 50);
            $table->boolean('ver')->default(false);
            $table->boolean('crear')->default(false);
            $table->boolean('editar')->default(false);
            $table->boolean('eliminar')->default(false);
            $table->timestamps();

            $table->unique(['rol_id', 'modulo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rol_modulo_permiso');
    }
};
