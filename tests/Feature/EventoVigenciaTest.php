<?php

namespace Tests\Feature;

use App\Models\EventoConfiguracion;
use Tests\TestCase;

class EventoVigenciaTest extends TestCase
{
    public function test_evento_con_inicio_futuro_sigue_aceptando_preinscripcion(): void
    {
        // El inicio en el futuro NO debe bloquear (equipos que se anticipan).
        $evento = new EventoConfiguracion([
            'fecha_inicio' => now()->addDays(10),
            'fecha_fin' => now()->addDays(20),
        ]);

        $this->assertTrue($evento->estaVigente());
    }

    public function test_evento_sin_fechas_siempre_vigente(): void
    {
        $evento = new EventoConfiguracion;

        $this->assertTrue($evento->estaVigente());
    }

    public function test_evento_sin_fecha_fin_siempre_vigente(): void
    {
        $evento = new EventoConfiguracion(['fecha_inicio' => now()->addDays(5)]);

        $this->assertTrue($evento->estaVigente());
    }

    public function test_evento_en_su_ultimo_dia_sigue_vigente(): void
    {
        // Durante todo el ultimo dia (fecha_fin = hoy) aun se acepta.
        $evento = new EventoConfiguracion(['fecha_fin' => now()]);

        $this->assertTrue($evento->estaVigente());
    }

    public function test_evento_finalizado_no_vigente(): void
    {
        // Pasada la fecha de fin ya no se aceptan inscripciones.
        $evento = new EventoConfiguracion([
            'fecha_inicio' => now()->subDays(20),
            'fecha_fin' => now()->subDay(),
        ]);

        $this->assertFalse($evento->estaVigente());
    }
}
