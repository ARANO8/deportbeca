<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
        'status',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class, 'rol_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function permisosModulo()
    {
        return $this->hasMany(RolModuloPermiso::class, 'rol_id');
    }

    public function tienePermiso($modulo, $permiso)
    {
        $permisoObj = RolModuloPermiso::where('rol_id', $this->id)
            ->where('modulo', $modulo)
            ->first();

        if (! $permisoObj) {
            return false;
        }

        return $permisoObj->$permiso;
    }
}
