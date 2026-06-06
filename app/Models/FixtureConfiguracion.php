<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FixtureConfiguracion extends Model
{
    protected $table = 'fixture_configuraciones';

    protected $fillable = [
        'evento_configuracion_id', 'disciplina_id', 'formato',
        'puntos_ganador', 'puntos_empate', 'puntos_perdedor',
        'local_visitante_alternado', 'mostrar_tabla_posiciones',
        'color_primario', 'color_secundario',
    ];

    protected $casts = [
        'local_visitante_alternado' => 'boolean',
        'mostrar_tabla_posiciones' => 'boolean',
    ];

    public function evento()
    {
        return $this->belongsTo(EventoConfiguracion::class, 'evento_configuracion_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Discipline::class);
    }
}
