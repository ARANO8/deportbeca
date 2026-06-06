<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evento_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_evento'); // intercarreras, olimpiadas, interauxiliares
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(false);
            $table->string('codigo_acceso', 20)->unique()->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->json('disciplinas_ids')->nullable();
            $table->integer('max_integrantes_grupal')->default(8);
            $table->integer('min_integrantes_grupal')->default(4);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evento_configuraciones');
    }
};
