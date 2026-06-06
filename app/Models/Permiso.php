<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';

    protected $fillable = [
        'nombre',
        'clave',
        'descripcion',
    ];

    // Sistema B (rol_permiso) removido — se consolido en rol_modulo_permiso (Sistema A).
    // Ver: app/Models/RolModuloPermiso.php y app/Models/Rol.php::tienePermiso()
}
