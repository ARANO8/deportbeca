<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Eliminar tabla existente
        Schema::dropIfExists('preinscripcion_integrantes');

        // Crear nueva tabla
        Schema::create('preinscripcion_integrantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('preinscripcion_id')->constrained('preinscripciones')->onDelete('cascade');
            $table->string('nombre');
            $table->string('ci');
            $table->boolean('es_capitan')->default(false);

            // Documentos
            $table->string('documento_ci_path')->nullable();
            $table->string('documento_seguro_path')->nullable();
            $table->string('documento_matricula_path')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        // No implementamos down para no perder datos
    }
};
