<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreinscripcionIntegrante extends Model
{
    protected $table = 'preinscripcion_integrantes';

    protected $fillable = [
        'preinscripcion_id',
        'nombre',
        'ci',
        'es_capitan',
        'documento_ci_path',
        'documento_seguro_path',
        'documento_matricula_path',
    ];

    public function preinscripcion()
    {
        return $this->belongsTo(Preinscripcion::class);
    }

    // NOTA: carrera_id no existe en preinscripcion_integrantes.
    // La carrera de un integrante se obtiene a través de su preinscripcion:
    //   $integrante->preinscripcion->carrera
}
