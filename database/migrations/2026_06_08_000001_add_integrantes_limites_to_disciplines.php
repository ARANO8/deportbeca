<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rango oficial de integrantes por disciplina y modalidad.
        // Nullable: solo se llena la modalidad que aplica (un deporte grupal no
        // necesita rango individual y viceversa).
        Schema::table('disciplines', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_integrantes_grupal')->nullable();
            $table->unsignedSmallInteger('max_integrantes_grupal')->nullable();
            $table->unsignedSmallInteger('min_integrantes_individual')->nullable();
            $table->unsignedSmallInteger('max_integrantes_individual')->nullable();
        });

        // Override por evento (cupo del torneo). Si queda en null se hereda el
        // rango oficial de la disciplina. El controlador valida que no exceda
        // el rango de la disciplina.
        Schema::table('evento_configuracion_disciplinas', function (Blueprint $table) {
            $table->unsignedSmallInteger('min_integrantes_grupal')->nullable();
            $table->unsignedSmallInteger('max_integrantes_grupal')->nullable();
            $table->unsignedSmallInteger('min_integrantes_individual')->nullable();
            $table->unsignedSmallInteger('max_integrantes_individual')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('disciplines', function (Blueprint $table) {
            $table->dropColumn([
                'min_integrantes_grupal',
                'max_integrantes_grupal',
                'min_integrantes_individual',
                'max_integrantes_individual',
            ]);
        });

        Schema::table('evento_configuracion_disciplinas', function (Blueprint $table) {
            $table->dropColumn([
                'min_integrantes_grupal',
                'max_integrantes_grupal',
                'min_integrantes_individual',
                'max_integrantes_individual',
            ]);
        });
    }
};
