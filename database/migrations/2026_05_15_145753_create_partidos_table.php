<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('series')->onDelete('cascade');
            $table->foreignId('evento_configuracion_id')->constrained('evento_configuraciones');
            $table->foreignId('disciplina_id')->constrained('disciplines');
            $table->foreignId('equipo_local_id')->constrained('preinscripciones');
            $table->foreignId('equipo_visitante_id')->constrained('preinscripciones');
            $table->foreignId('lugar_id')->nullable()->constrained('lugares');
            $table->integer('jornada');
            $table->date('fecha')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->integer('goles_local')->nullable();
            $table->integer('goles_visitante')->nullable();
            $table->enum('estado', ['programado', 'en_curso', 'finalizado', 'suspendido', 'cancelado'])->default('programado');
            $table->boolean('es_descanso')->default(false);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['serie_id', 'jornada']);
            $table->index('estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('partidos');
    }
};
