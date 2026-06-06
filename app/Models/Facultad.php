<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'facultades';

    protected $fillable = [
        'codigo',
        'nombre',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relación con carreras
    public function carreras()
    {
        return $this->hasMany(Carrera::class, 'facultad_id');
    }

    // NOTA: users.facultad_id no existe en la migración actual.
    // Esta relación se puede activar agregando la columna en una migración futura.
    // public function users()
    // {
    //     return $this->hasMany(User::class, 'facultad_id');
    // }

    // Scope para activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope para inactivos
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
