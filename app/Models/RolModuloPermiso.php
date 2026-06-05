<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolModuloPermiso extends Model
{
    use HasFactory;

    protected $table = 'rol_modulo_permiso';

    protected $fillable = [
        'rol_id',
        'modulo',
        'ver',
        'crear',
        'editar',
        'eliminar',
    ];

    protected $casts = [
        'ver' => 'boolean',
        'crear' => 'boolean',
        'editar' => 'boolean',
        'eliminar' => 'boolean',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }
}
