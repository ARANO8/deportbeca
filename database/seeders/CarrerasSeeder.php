<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrerasSeeder extends Seeder
{
    public function run(): void
    {
        $porFacultad = [
            'FCE' => [
                ['codigo' => 'ECO',  'nombre' => 'Economía'],
                ['codigo' => 'ADM',  'nombre' => 'Administración de Empresas'],
                ['codigo' => 'CONT', 'nombre' => 'Contaduría Pública'],
                ['codigo' => 'COM',  'nombre' => 'Comercio Exterior'],
            ],
            'FDC' => [
                ['codigo' => 'DER',  'nombre' => 'Derecho'],
                ['codigo' => 'CPOL', 'nombre' => 'Ciencias Políticas'],
            ],
            'FCPN' => [
                ['codigo' => 'MAT',  'nombre' => 'Matemática'],
                ['codigo' => 'FIS',  'nombre' => 'Física'],
                ['codigo' => 'QUI',  'nombre' => 'Química'],
                ['codigo' => 'BIO',  'nombre' => 'Biología'],
                ['codigo' => 'INF',  'nombre' => 'Informática'],
                ['codigo' => 'EST',  'nombre' => 'Estadística'],
            ],
            'FHE' => [
                ['codigo' => 'PSI',  'nombre' => 'Psicología'],
                ['codigo' => 'EDU',  'nombre' => 'Ciencias de la Educación'],
                ['codigo' => 'COM2', 'nombre' => 'Comunicación Social'],
                ['codigo' => 'BIB',  'nombre' => 'Bibliotecología'],
            ],
            'FCS' => [
                ['codigo' => 'SOC',  'nombre' => 'Sociología'],
                ['codigo' => 'TS',   'nombre' => 'Trabajo Social'],
                ['codigo' => 'ARQ2', 'nombre' => 'Arqueología'],
            ],
            'FCT' => [
                ['codigo' => 'IND',  'nombre' => 'Ingeniería Industrial'],
                ['codigo' => 'ELC',  'nombre' => 'Electrónica'],
                ['codigo' => 'MEC',  'nombre' => 'Mecánica'],
                ['codigo' => 'PROD', 'nombre' => 'Ingeniería de Producción'],
            ],
            'FAADU' => [
                ['codigo' => 'ARQ',  'nombre' => 'Arquitectura'],
                ['codigo' => 'DIS',  'nombre' => 'Diseño Gráfico'],
                ['codigo' => 'URB',  'nombre' => 'Urbanismo'],
            ],
            'FM' => [
                ['codigo' => 'MED',  'nombre' => 'Medicina'],
                ['codigo' => 'ENF',  'nombre' => 'Enfermería'],
                ['codigo' => 'NUT',  'nombre' => 'Nutrición y Dietética'],
                ['codigo' => 'TM',   'nombre' => 'Tecnología Médica'],
            ],
            'FAG' => [
                ['codigo' => 'AGR',  'nombre' => 'Ingeniería Agronómica'],
                ['codigo' => 'FOR',  'nombre' => 'Ingeniería Forestal'],
                ['codigo' => 'VET',  'nombre' => 'Medicina Veterinaria y Zootecnia'],
            ],
            'FI' => [
                ['codigo' => 'IC',   'nombre' => 'Ingeniería Civil'],
                ['codigo' => 'IE',   'nombre' => 'Ingeniería Eléctrica'],
                ['codigo' => 'ISW',  'nombre' => 'Ingeniería de Sistemas'],
                ['codigo' => 'IPT',  'nombre' => 'Ingeniería Petrolera'],
                ['codigo' => 'IM',   'nombre' => 'Ingeniería Metalúrgica'],
            ],
            'FOE' => [
                ['codigo' => 'ODO',  'nombre' => 'Odontología'],
            ],
        ];

        foreach ($porFacultad as $codigoFac => $carreras) {
            $facultadId = DB::table('facultades')->where('codigo', $codigoFac)->value('id');
            if (! $facultadId) {
                continue;
            }

            foreach ($carreras as $row) {
                DB::table('carreras')->updateOrInsert(
                    ['codigo' => $row['codigo']],
                    array_merge($row, [
                        'facultad_id' => $facultadId,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }
    }
}
