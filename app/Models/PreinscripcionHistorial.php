<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreinscripcionHistorial extends Model
{
    protected $table = 'preinscripcion_historial';

    protected $fillable = [
        'preinscripcion_id',
        'user_id',
        'estado_anterior',
        'estado_nuevo',
        'motivo',
    ];

    public function preinscripcion()
    {
        return $this->belongsTo(Preinscripcion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
