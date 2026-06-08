<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Seeder;

class DisciplinasMapasSeeder extends Seeder
{
    public function run(): void
    {
        $mapas = [
            'Voleibol' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4336977280886!2d-68.12877148835632!3d-16.504188394021998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f206505af984f%3A0xa8cdd0c3fc238d87!2sColiseo%20Universitario!5e0!3m2!1ses!2sbo!4v1775747940032!5m2!1ses!2sbo',
            'Futsal' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4336977280886!2d-68.12877148835632!3d-16.504188394021998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f206505af984f%3A0xa8cdd0c3fc238d87!2sColiseo%20Universitario!5e0!3m2!1ses!2sbo!4v1775747940032!5m2!1ses!2sbo',
            'Taekwondo' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4336977280886!2d-68.12877148835632!3d-16.504188394021998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f206505af984f%3A0xa8cdd0c3fc238d87!2sColiseo%20Universitario!5e0!3m2!1ses!2sbo!4v1775747940032!5m2!1ses!2sbo',
            'Fútbol' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7649.491805635443!2d-68.07664811611176!3d-16.538920849442768!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f21da1940c6e3%3A0xac5edb79c0bd59ae!2sCampo%20de%20futbol%20UMSA%20Cota%20Cota!5e0!3m2!1ses!2sbo!4v1778078441848!5m2!1ses!2sbo',
            'Ajedrez' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.4215074603253!2d-68.12996439999999!3d-16.504804600000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f20652721ec61%3A0xc6f71345411abf0e!2sBiblioteca%20Central%20-%20UMSA!5e0!3m2!1ses!2sbo!4v1778078267567!5m2!1ses!2sbo',
            'Atletismo' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.527016439852!2d-68.1252155248549!3d-16.49947048424415!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f2068f229a7eb%3A0xc25d2f7d5b2c81ce!2sEstadio%20Hernando%20Siles!5e0!3m2!1ses!2sbo!4v1778078316405!5m2!1ses!2sbo',
            'Gimnasio' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3825.421609524505!2d-68.13253932465766!3d-16.504799440849496!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x915f2064df652367%3A0xddb4c11ab8c8d666!2sMonoblock%20UMSA!5e0!3m2!1ses!2sbo!4v1778079725036!5m2!1ses!2sbo',
        ];

        foreach ($mapas as $nombre => $url) {
            $discipline = Discipline::where('nombre', 'like', "%{$nombre}%")->first();
            if ($discipline) {
                $discipline->ubicacion_mapa = $url;
                $discipline->save();
                $this->command->info("✅ Actualizado: {$nombre}");
            } else {
                $this->command->warn("⚠️ No encontrado: {$nombre}");
            }
        }
        
        $this->command->info("\n🎉 ¡Mapas actualizados correctamente!");
    }
}