<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('preinscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_evento');
            $table->string('tipo_inscripcion'); // individual, grupal
            $table->foreignId('disciplina_id')->constrained('disciplines');
            $table->string('nombre_equipo')->nullable();
            $table->integer('cantidad_integrantes')->nullable();

            // Datos del representante
            $table->string('representante_nombre');
            $table->string('representante_ci');
            $table->string('representante_email');
            $table->string('representante_telefono');

            // Campo dinámico según evento
            $table->foreignId('facultad_id')->nullable()->constrained('facultades');
            $table->foreignId('carrera_id')->nullable()->constrained('carreras');

            // Documentos
            $table->string('documento_ci_path')->nullable();
            $table->string('documento_seguro_path')->nullable();
            $table->string('documento_matricula_path')->nullable();
            $table->string('documento_aval_path')->nullable();

            // Estado
            $table->enum('estado', ['pendiente', 'habilitado', 'observado'])->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->string('codigo_inscripcion', 20)->unique();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('preinscripciones');
    }
};
