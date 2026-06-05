<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Preinscripcion extends Model
{
    protected $table = 'preinscripciones';

    protected $fillable = [
        'tipo_evento', 'tipo_inscripcion', 'disciplina_id', 'nombre_equipo',
        'cantidad_integrantes', 'representante_nombre', 'representante_ci',
        'representante_email', 'representante_telefono',
        'facultad_id', 'carrera_id',
        'documento_ci_path', 'documento_seguro_path', 'documento_matricula_path',
        'documento_aval_path', 'estado', 'observaciones', 'codigo_inscripcion',
    ];

    const ESTADO_PENDIENTE = 'pendiente';

    const ESTADO_HABILITADO = 'habilitado';

    const ESTADO_OBSERVADO = 'observado';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->codigo_inscripcion)) {
                $model->codigo_inscripcion = 'INS-'.strtoupper(Str::random(8));
            }
        });
    }

    // ========== RELACIONES ==========

    public function disciplina()
    {
        return $this->belongsTo(Discipline::class);
    }

    public function facultad()
    {
        return $this->belongsTo(Facultad::class);
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class);
    }

    public function integrantes()
    {
        return $this->hasMany(PreinscripcionIntegrante::class);
    }

    public function historial()
    {
        return $this->hasMany(PreinscripcionHistorial::class)->orderBy('created_at', 'desc');
    }

    // ========== SCOPES ==========

    public function scopeHabilitados($query)
    {
        return $query->where('estado', self::ESTADO_HABILITADO);
    }

    public function scopeObservados($query)
    {
        return $query->where('estado', self::ESTADO_OBSERVADO);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    // ========== ACCESORS (NUEVO) ==========

    /**
     * Obtener nombre del participante (equipo o individual)
     */
    public function getNombreParticipanteAttribute()
    {
        return $this->tipo_inscripcion == 'individual'
            ? $this->representante_nombre
            : $this->nombre_equipo;
    }
}
