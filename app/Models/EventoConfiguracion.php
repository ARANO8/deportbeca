<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EventoConfiguracion extends Model
{
    protected $table = 'evento_configuraciones';

    protected $fillable = [
        'tipo_evento',
        'nombre',
        'descripcion',
        'activo',
        'codigo_acceso',
        'fecha_inicio',
        'fecha_fin',
        'disciplinas_ids',
        'max_integrantes_grupal',
        'min_integrantes_grupal',
        'max_inscripciones',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'disciplinas_ids' => 'array',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // ========== RELACIONES ==========

    /**
     * Relación con las series (fixtures)
     */
    public function series()
    {
        return $this->hasMany(Serie::class, 'evento_configuracion_id');
    }

    /**
     * Relación con los partidos a través de las series
     */
    public function partidos()
    {
        return $this->hasManyThrough(Partido::class, Serie::class, 'evento_configuracion_id', 'serie_id');
    }
    // ========== MÉTODOS DE NEGOCIO ==========

    /**
     * Genera un código de acceso único para el evento.
     * Formato: XXX-XXXXXX (prefijo derivado del tipo + 6 caracteres aleatorios).
     */
    public static function generateCodigoAcceso(string $tipoEvento): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $tipoEvento), 0, 3));
        $prefix = $prefix ?: 'EVT';

        return $prefix.'-'.strtoupper(Str::random(6));
    }

    /**
     * Verifica si el evento está dentro del rango de fechas vigente.
     */
    public function estaVigente(): bool
    {
        $hoy = now();

        if (! $this->fecha_inicio && ! $this->fecha_fin) {
            return true;
        }

        if ($this->fecha_inicio && ! $this->fecha_fin) {
            return $hoy >= $this->fecha_inicio;
        }

        if ($this->fecha_inicio && $this->fecha_fin) {
            return $hoy >= $this->fecha_inicio && $hoy <= $this->fecha_fin;
        }

        return false;
    }

    /**
     * Retorna las disciplinas habilitadas para este evento.
     * Reemplaza la llamada que antes fallaba porque el método no existía.
     */
    public function disciplinasPermitidas()
    {
        $ids = is_array($this->disciplinas_ids) ? $this->disciplinas_ids : [];

        if (empty($ids)) {
            return collect();
        }

        return Discipline::whereIn('id', $ids)
            ->where('status', 'active')
            ->orderBy('nombre')
            ->get();
    }

    public function inscripcionesActuales(): int
    {
        return Preinscripcion::where('tipo_evento', $this->tipo_evento)
            ->whereIn('estado', [Preinscripcion::ESTADO_PENDIENTE, Preinscripcion::ESTADO_HABILITADO])
            ->count();
    }
}
