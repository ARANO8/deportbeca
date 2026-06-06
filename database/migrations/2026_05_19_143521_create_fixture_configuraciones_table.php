<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fixture_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_configuracion_id')->constrained('evento_configuraciones')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplines')->onDelete('cascade');
            $table->enum('formato', ['liga', 'eliminatoria', 'mixto'])->default('liga');
            $table->integer('puntos_ganador')->default(3);
            $table->integer('puntos_empate')->default(1);
            $table->integer('puntos_perdedor')->default(0);
            $table->boolean('local_visitante_alternado')->default(true);
            $table->boolean('mostrar_tabla_posiciones')->default(true);
            $table->string('color_primario', 20)->default('#667eea');
            $table->string('color_secundario', 20)->default('#764ba2');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fixture_configuraciones');
    }
};
