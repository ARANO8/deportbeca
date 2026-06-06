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

    // es_super_admin queda fuera de fillable a proposito: no debe asignarse
    // por mass-assignment desde formularios. Se gestiona solo por migracion/seeder.
    protected $casts = [
        'es_super_admin' => 'boolean',
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
