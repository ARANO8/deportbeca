<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinasSeeder extends Seeder
{
    public function run(): void
    {
        // ─── DISCIPLINAS GRUPALES (sin parent) ─────────────────────────────
        $grupales = [
            ['codigo' => 'FUT',  'nombre' => 'Fútbol',              'descripcion' => 'Disciplina grupal — 11 jugadores por equipo'],
            ['codigo' => 'FUS',  'nombre' => 'Fútbol Sala',         'descripcion' => 'Disciplina grupal — 5 jugadores por equipo'],
            ['codigo' => 'BAS',  'nombre' => 'Básquetbol',          'descripcion' => 'Disciplina grupal — 5 jugadores por equipo'],
            ['codigo' => 'VOL',  'nombre' => 'Voleibol',            'descripcion' => 'Disciplina grupal — 6 jugadores por equipo'],
            ['codigo' => 'TEN',  'nombre' => 'Tenis de Mesa Dobles', 'descripcion' => 'Disciplina grupal — parejas'],
            ['codigo' => 'AJE',  'nombre' => 'Ajedrez Parejas',     'descripcion' => 'Disciplina grupal — parejas'],
        ];

        foreach ($grupales as $row) {
            DB::table('disciplines')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'parent_id' => null,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // ─── DISCIPLINAS INDIVIDUALES (con sub-disciplinas) ────────────────

        // Atletismo (padre)
        DB::table('disciplines')->updateOrInsert(
            ['codigo' => 'ATL'],
            [
                'codigo' => 'ATL',
                'nombre' => 'Atletismo',
                'descripcion' => 'Disciplinas de pista y campo',
                'parent_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $atletismoId = DB::table('disciplines')->where('codigo', 'ATL')->value('id');

        $subAtletismo = [
            ['codigo' => 'ATL-100',  'nombre' => 'Atletismo 100 m planos',        'descripcion' => 'Carrera de velocidad individual'],
            ['codigo' => 'ATL-200',  'nombre' => 'Atletismo 200 m planos',        'descripcion' => 'Carrera de velocidad individual'],
            ['codigo' => 'ATL-400',  'nombre' => 'Atletismo 400 m planos',        'descripcion' => 'Carrera de media distancia'],
            ['codigo' => 'ATL-800',  'nombre' => 'Atletismo 800 m',               'descripcion' => 'Carrera de media distancia'],
            ['codigo' => 'ATL-1500', 'nombre' => 'Atletismo 1500 m',              'descripcion' => 'Carrera de fondo'],
            ['codigo' => 'ATL-OBS',  'nombre' => 'Atletismo 100 m con obstáculos', 'descripcion' => 'Carrera con vallas individual'],
            ['codigo' => 'ATL-LONG', 'nombre' => 'Atletismo Salto Largo',         'descripcion' => 'Atletismo de campo — salto'],
            ['codigo' => 'ATL-ALT',  'nombre' => 'Atletismo Salto Alto',          'descripcion' => 'Atletismo de campo — salto'],
        ];

        foreach ($subAtletismo as $row) {
            DB::table('disciplines')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'parent_id' => $atletismoId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // Natación (padre)
        DB::table('disciplines')->updateOrInsert(
            ['codigo' => 'NAT'],
            [
                'codigo' => 'NAT',
                'nombre' => 'Natación',
                'descripcion' => 'Disciplinas acuáticas individuales',
                'parent_id' => null,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $natacionId = DB::table('disciplines')->where('codigo', 'NAT')->value('id');

        $subNatacion = [
            ['codigo' => 'NAT-50L',   'nombre' => 'Natación 50 m libre',        'descripcion' => 'Prueba de velocidad'],
            ['codigo' => 'NAT-100L',  'nombre' => 'Natación 100 m libre',       'descripcion' => 'Prueba estilo libre'],
            ['codigo' => 'NAT-200E',  'nombre' => 'Natación 200 m espalda',     'descripcion' => 'Prueba estilo espalda'],
            ['codigo' => 'NAT-200M',  'nombre' => 'Natación 200 m mariposa',    'descripcion' => 'Prueba estilo mariposa'],
        ];

        foreach ($subNatacion as $row) {
            DB::table('disciplines')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'parent_id' => $natacionId,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        // Individuales sin sub-disciplinas
        $individuales = [
            ['codigo' => 'TEN1', 'nombre' => 'Tenis de Mesa Individual', 'descripcion' => 'Disciplina individual de mesa'],
            ['codigo' => 'AJE1', 'nombre' => 'Ajedrez Individual',       'descripcion' => 'Disciplina individual de ajedrez'],
            ['codigo' => 'LUC',  'nombre' => 'Lucha Olímpica',           'descripcion' => 'Disciplina de combate individual'],
            ['codigo' => 'JUD',  'nombre' => 'Judo',                     'descripcion' => 'Disciplina de combate individual'],
        ];

        foreach ($individuales as $row) {
            DB::table('disciplines')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'parent_id' => null,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
