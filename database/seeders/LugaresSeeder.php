<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LugaresSeeder extends Seeder
{
    public function run(): void
    {
        $lugares = [
            [
                'codigo' => 'COL-PRIN',
                'nombre' => 'Coliseo Principal UMSA',
                'direccion' => 'Av. Villazón s/n, Campus Universitario, La Paz',
                'descripcion' => 'Coliseo cubierto multiusos. Capacidad para básquetbol, voleibol y tenis de mesa.',
            ],
            [
                'codigo' => 'CANCHA-FUT-1',
                'nombre' => 'Cancha de Fútbol Nro. 1',
                'direccion' => 'Campus Universitario, La Paz',
                'descripcion' => 'Cancha de gras sintético — fútbol 11.',
            ],
            [
                'codigo' => 'CANCHA-FUT-2',
                'nombre' => 'Cancha de Fútbol Nro. 2',
                'direccion' => 'Campus Universitario, La Paz',
                'descripcion' => 'Cancha secundaria de fútbol 11.',
            ],
            [
                'codigo' => 'CANCHA-FUS-1',
                'nombre' => 'Cancha Fútbol Sala Nro. 1',
                'direccion' => 'Coliseo Principal, Campus UMSA, La Paz',
                'descripcion' => 'Cancha de fútbol sala (futsal) — superficie de parqué.',
            ],
            [
                'codigo' => 'PISTA-ATL',
                'nombre' => 'Pista de Atletismo',
                'direccion' => 'Estadio Universitario, La Paz',
                'descripcion' => 'Pista de tartán de 400 m con 8 carriles. Usada para pruebas de pista.',
            ],
            [
                'codigo' => 'PISCINA',
                'nombre' => 'Piscina Universitaria',
                'direccion' => 'Campus Universitario, La Paz',
                'descripcion' => 'Piscina semiolímpica de 25 m — 6 carriles.',
            ],
            [
                'codigo' => 'GIM-AJE',
                'nombre' => 'Sala de Ajedrez y Tenis de Mesa',
                'direccion' => 'Edificio de Deportes, Campus UMSA, La Paz',
                'descripcion' => 'Sala interior con mesas de tenis de mesa y tableros de ajedrez.',
            ],
        ];

        foreach ($lugares as $row) {
            DB::table('lugares')->updateOrInsert(
                ['codigo' => $row['codigo']],
                array_merge($row, [
                    'embed_mapa' => null,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'deleted_at' => null,
                ])
            );
        }
    }
}
