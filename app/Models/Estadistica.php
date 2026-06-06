<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estadistica extends Model
{
    protected $table = 'estadisticas';

    protected $fillable = [
        'serie_id', 'preinscripcion_id', 'nombre_equipo',
        'pj', 'pg', 'pe', 'pp', 'gf', 'gc', 'dg', 'pts',
        'tarjetas_amarillas', 'tarjetas_rojas',
        'posicion_final', 'tiempo', 'marca',
        'extra_data',
    ];

    protected $casts = [
        'extra_data' => 'array',
    ];

    public function serie()
    {
        return $this->belongsTo(Serie::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Preinscripcion::class, 'preinscripcion_id');
    }
}
