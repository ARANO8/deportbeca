<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Elimina el campo legacy users.role (string) y consolida en rol_id (FK -> roles).
 *
 * Antes de eliminar la columna, rellena rol_id donde falte mapeando el string
 * legacy al rol correspondiente de la tabla roles. La FK rol_id queda como
 * unica fuente de verdad para la autorizacion.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Mapeo del string legacy al nombre del rol en la tabla roles
        $mapa = [
            'admin' => 'Administrador',
            'profe' => 'Instructor',
            'profesor' => 'Instructor',
            'secretaria' => 'Secretario',
            'secretario' => 'Secretario',
        ];

        foreach ($mapa as $legacy => $nombreRol) {
            $rolId = DB::table('roles')
                ->whereRaw('LOWER(nombre) = ?', [strtolower($nombreRol)])
                ->value('id');

            if ($rolId) {
                DB::table('users')
                    ->where('role', $legacy)
                    ->whereNull('rol_id')
                    ->update(['rol_id' => $rolId]);
            }
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('amaterno');
        });

        // Reconstruir el string legacy desde el rol asignado
        $mapa = [
            'Administrador' => 'admin',
            'Instructor' => 'profe',
            'Secretario' => 'secretaria',
        ];

        foreach ($mapa as $nombreRol => $legacy) {
            $rolId = DB::table('roles')
                ->whereRaw('LOWER(nombre) = ?', [strtolower($nombreRol)])
                ->value('id');

            if ($rolId) {
                DB::table('users')
                    ->where('rol_id', $rolId)
                    ->update(['role' => $legacy]);
            }
        }
    }
};
