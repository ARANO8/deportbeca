<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'evento_configuracion_id',
        'disciplina_id',
        'nombre_serie',
        'numero_serie',
        'cantidad_equipos',
        'estado',
        'tipo_competencia',
        'cuantos_clasifican',
        'observaciones',
    ];

    // ========== RELACIONES ==========

    public function eventoConfiguracion()
    {
        return $this->belongsTo(EventoConfiguracion::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function partidos()
    {
        return $this->hasMany(Partido::class);
    }

    public function estadisticas()
    {
        return $this->hasMany(Estadistica::class);
    }

    /**
     * Todos los equipos/participantes asignados a esta serie (FK real).
     * Reemplaza el JSON equipos_ids.
     */
    public function preinscripciones()
    {
        return $this->belongsToMany(
            Preinscripcion::class,
            'serie_preinscripciones',
            'serie_id',
            'preinscripcion_id'
        )->withPivot(['es_clasificado', 'orden'])
            ->orderByPivot('orden')
            ->withTimestamps();
    }

    /**
     * Solo los equipos que clasificaron a la siguiente fase.
     * Reemplaza el JSON clasificados_ids.
     */
    public function clasificados()
    {
        return $this->belongsToMany(
            Preinscripcion::class,
            'serie_preinscripciones',
            'serie_id',
            'preinscripcion_id'
        )->withPivot(['es_clasificado', 'orden'])
            ->wherePivot('es_clasificado', true)
            ->orderByPivot('orden')
            ->withTimestamps();
    }

    // ========== ACCESSORS (compatibilidad hacia atras) ==========

    /**
     * $serie->equipos sigue funcionando en todas las vistas existentes.
     */
    public function getEquiposAttribute()
    {
        return $this->preinscripciones;
    }

    // ========== COMPUTED PROPERTIES ==========

    /**
     * Tabla de posiciones ordenada por pts, dg, gf.
     */
    public function getTablaPosicionesAttribute()
    {
        return $this->estadisticas()
            ->with('equipo')
            ->orderBy('pts', 'desc')
            ->orderBy('dg', 'desc')
            ->orderBy('gf', 'desc')
            ->get();
    }
}
