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
        'max_integrantes_grupal',
        'min_integrantes_grupal',
        'max_inscripciones',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // ========== RELACIONES ==========

    /**
     * Disciplinas habilitadas para este evento (many-to-many con FK real).
     */
    public function disciplines()
    {
        return $this->belongsToMany(
            Discipline::class,
            'evento_configuracion_disciplinas',
            'evento_configuracion_id',
            'discipline_id'
        )->withPivot([
            'min_integrantes_grupal',
            'max_integrantes_grupal',
            'min_integrantes_individual',
            'max_integrantes_individual',
        ])->withTimestamps();
    }

    /**
     * Series (fixtures) de este evento.
     */
    public function series()
    {
        return $this->hasMany(Serie::class, 'evento_configuracion_id');
    }

    /**
     * Partidos a traves de las series.
     */
    public function partidos()
    {
        return $this->hasManyThrough(
            Partido::class,
            Serie::class,
            'evento_configuracion_id',
            'serie_id'
        );
    }

    // ========== METODOS DE NEGOCIO ==========

    /**
     * Retorna las disciplinas activas habilitadas para este evento.
     * Usa la relacion many-to-many normalizada (reemplaza whereIn sobre JSON).
     */
    public function disciplinasPermitidas()
    {
        return $this->disciplines()
            ->where('status', 'active')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Rango efectivo de integrantes [min, max, permite] para una disciplina y
     * modalidad ('grupal'|'individual') en este evento.
     *
     * Prioridad: override del evento (pivot) -> rango oficial de la disciplina.
     * Si la disciplina no define rango para la modalidad, se aplica un fallback
     * retrocompatible (evento para grupal; 1 persona para individual).
     */
    public function rangoIntegrantes(Discipline $discipline, string $modalidad): array
    {
        $permite = $discipline->permiteModalidad($modalidad);

        $min = $discipline->{"min_integrantes_{$modalidad}"};
        $max = $discipline->{"max_integrantes_{$modalidad}"};

        if ($discipline->pivot) {
            $min = $discipline->pivot->{"min_integrantes_{$modalidad}"} ?? $min;
            $max = $discipline->pivot->{"max_integrantes_{$modalidad}"} ?? $max;
        }

        if (! $discipline->tieneRango($modalidad)) {
            if ($modalidad === 'grupal') {
                $min = $this->min_integrantes_grupal ?? 2;
                $max = $this->max_integrantes_grupal ?? 12;
            } else {
                $min = 1;
                $max = 1;
            }
        } else {
            $min = $min ?? 1;
            $max = $max ?? max((int) $min, (int) ($this->max_integrantes_grupal ?? 30));
        }

        return ['min' => (int) $min, 'max' => (int) $max, 'permite' => $permite];
    }

    /**
     * Genera un codigo de acceso unico para el evento.
     */
    public static function generateCodigoAcceso(string $tipoEvento): string
    {
        $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $tipoEvento), 0, 3));
        $prefix = $prefix ?: 'EVT';

        return $prefix.'-'.strtoupper(Str::random(6));
    }

    /**
     * Verifica si el evento esta dentro del rango de fechas vigente.
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

    public function inscripcionesActuales(): int
    {
        return Preinscripcion::where('tipo_evento', $this->tipo_evento)
            ->whereIn('estado', [Preinscripcion::ESTADO_PENDIENTE, Preinscripcion::ESTADO_HABILITADO])
            ->count();
    }
}
