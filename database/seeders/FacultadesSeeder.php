<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultadesSeeder extends Seeder
{
    public function run(): void
    {
        $facultades = [
            ['codigo' => 'FCE',   'nombre' => 'Facultad de Ciencias Económicas y Financieras'],
            ['codigo' => 'FDC',   'nombre' => 'Facultad de Derecho y Ciencias Políticas'],
            ['codigo' => 'FCPN',  'nombre' => 'Facultad de Ciencias Puras y Naturales'],
            ['codigo' => 'FHE',   'nombre' => 'Facultad de Humanidades y Ciencias de la Educación'],
            ['codigo' => 'FCS',   'nombre' => 'Facultad de Ciencias Sociales'],
            ['codigo' => 'FCT',   'nombre' => 'Facultad de Ciencias y Tecnología'],
            ['codigo' => 'FAADU', 'nombre' => 'Facultad de Arquitectura, Artes, Diseño y Urbanismo'],
            ['codigo' => 'FM',    'nombre' => 'Facultad de Medicina, Enfermería, Nutrición y Tecnología Médica'],
            ['codigo' => 'FAG',   'nombre' => 'Facultad de Agronomía'],
            ['codigo' => 'FI',    'nombre' => 'Facultad de Ingeniería'],
            ['codigo' => 'FOE',   'nombre' => 'Facultad de Odontología'],
        ];

        foreach ($facultades as $row) {
            DB::table('facultades')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
