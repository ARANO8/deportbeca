<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega la columna rol_id a la tabla users.
 * La migración original (2026_05_15_000004) estaba mal nombrada:
 * creaba rol_modulo_permiso en vez de modificar users.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'rol_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('rol_id')
                    ->nullable()
                    ->after('status')
                    ->constrained('roles')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'rol_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['rol_id']);
                $table->dropColumn('rol_id');
            });
        }
    }
};
