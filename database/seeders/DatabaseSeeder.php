<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Orden de ejecución:
     *  1. Roles y permisos de módulo (primero, sin dependencias externas)
     *  2. Usuario administrador (depende de que exista el rol Administrador)
     *  3. Facultades
     *  4. Carreras (dependen de facultades)
     *  5. Disciplinas
     *  6. Lugares
     */
    public function run(): void
    {
        $this->call([
            RolesPermisosSeeder::class,
            AdminUserSeeder::class,
            FacultadesSeeder::class,
            CarrerasSeeder::class,
            DisciplinasSeeder::class,
            LugaresSeeder::class,
        ]);
    }
}
