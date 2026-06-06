<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('estadisticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serie_id')->constrained('series')->onDelete('cascade');
            $table->foreignId('preinscripcion_id')->constrained('preinscripciones')->onDelete('cascade');
            $table->string('nombre_equipo');
            $table->integer('pj')->default(0);
            $table->integer('pg')->default(0);
            $table->integer('pe')->default(0);
            $table->integer('pp')->default(0);
            $table->integer('gf')->default(0);
            $table->integer('gc')->default(0);
            $table->integer('dg')->default(0);
            $table->integer('pts')->default(0);
            $table->integer('tarjetas_amarillas')->default(0)->nullable();
            $table->integer('tarjetas_rojas')->default(0)->nullable();
            $table->json('extra_data')->nullable();
            $table->timestamps();
            $table->unique(['serie_id', 'preinscripcion_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('estadisticas');
    }
};
