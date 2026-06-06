<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_configuracion_id')->constrained('evento_configuraciones')->onDelete('cascade');
            $table->foreignId('disciplina_id')->constrained('disciplines')->onDelete('cascade');
            $table->string('nombre_serie', 50);
            $table->integer('numero_serie');
            $table->integer('cantidad_equipos');
            $table->json('equipos_ids');
            $table->enum('estado', ['pendiente', 'en_curso', 'finalizado'])->default('pendiente');
            $table->enum('tipo_competencia', ['todos_contra_todos', 'eliminatoria', 'mixto'])->default('todos_contra_todos');
            $table->integer('cuantos_clasifican')->default(2);
            $table->json('clasificados_ids')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('series');
    }
};
