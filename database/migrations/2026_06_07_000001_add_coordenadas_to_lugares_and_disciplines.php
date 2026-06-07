<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega coordenadas (latitud/longitud) a lugares y disciplines.
 *
 * Las coordenadas son la fuente de verdad para la ubicacion en el mapa
 * (seleccionada con Leaflet/OpenStreetMap). Reemplazan el flujo anterior de
 * pegar un embed/URL de Google Maps. Las columnas embed_mapa / ubicacion_mapa
 * se conservan nullable como respaldo durante la transicion.
 */
return new class extends Migration
{
    private array $tablas = ['lugares', 'disciplines'];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            if (! Schema::hasColumn($tabla, 'latitud')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->decimal('latitud', 10, 7)->nullable()->after('status');
                    $table->decimal('longitud', 10, 7)->nullable()->after('latitud');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            if (Schema::hasColumn($tabla, 'latitud')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->dropColumn(['latitud', 'longitud']);
                });
            }
        }
    }
};
