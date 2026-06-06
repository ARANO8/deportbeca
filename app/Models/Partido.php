<?php

// app/Models/Partido.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partido extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'serie_id',
        'equipo_local_id', 'equipo_visitante_id', 'lugar_id', 'jornada',
        'fecha', 'hora_inicio', 'hora_fin', 'goles_local', 'goles_visitante',
        'tarjetas_amarillas_local', 'tarjetas_rojas_local',
        'tarjetas_amarillas_visitante', 'tarjetas_rojas_visitante',
        'estado', 'es_descanso', 'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime',
        'es_descanso' => 'boolean',
    ];

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function equipoLocal()
    {
        return $this->belongsTo(Preinscripcion::class, 'equipo_local_id');
    }

    public function equipoVisitante()
    {
        return $this->belongsTo(Preinscripcion::class, 'equipo_visitante_id');
    }

    public function lugar()
    {
        return $this->belongsTo(Lugar::class);
    }

    // El evento y la disciplina se obtienen siempre a traves de la serie
    // (FK unica). Estos accessors mantienen compatibilidad con el codigo que
    // antes leia $partido->evento_configuracion_id / $partido->disciplina_id.
    public function getEventoConfiguracionIdAttribute()
    {
        return $this->serie?->evento_configuracion_id;
    }

    public function getDisciplinaIdAttribute()
    {
        return $this->serie?->disciplina_id;
    }
}
