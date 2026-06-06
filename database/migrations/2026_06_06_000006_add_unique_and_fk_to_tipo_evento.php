<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Integridad referencial para preinscripciones.tipo_evento.
 *
 * tipo_evento es la clave de negocio que vincula una preinscripcion con su
 * EventoConfiguracion. Hasta ahora era un string libre sin garantia de que el
 * evento existiera. Se agrega UNIQUE sobre evento_configuraciones.tipo_evento
 * y una FK desde preinscripciones para garantizar que toda inscripcion
 * referencie un evento configurado real.
 *
 * Se conserva la columna tipo_evento (en lugar de reemplazarla por un id) por
 * estar referenciada en mas de 30 archivos del sistema; la FK sobre el string
 * aporta la integridad sin reescribir esa logica.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->unique('tipo_evento');
        });

        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->foreign('tipo_evento')
                ->references('tipo_evento')->on('evento_configuraciones')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('preinscripciones', function (Blueprint $table) {
            $table->dropForeign(['tipo_evento']);
        });

        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->dropUnique(['tipo_evento']);
        });
    }
};
