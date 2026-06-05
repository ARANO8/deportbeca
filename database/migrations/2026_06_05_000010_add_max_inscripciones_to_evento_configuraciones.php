<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->integer('max_inscripciones')->nullable()->after('min_integrantes_grupal')
                ->comment('null = sin limite');
        });
    }

    public function down(): void
    {
        Schema::table('evento_configuraciones', function (Blueprint $table) {
            $table->dropColumn('max_inscripciones');
        });
    }
};
