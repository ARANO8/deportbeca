<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';

    protected $fillable = [
        'codigo',
        'nombre',
        'facultad_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facultad_id');
    }

    // NOTA: users.carrera_id no existe en la migración actual.
    // Esta relación se puede activar agregando la columna en una migración futura.
    // public function users()
    // {
    //     return $this->hasMany(User::class, 'carrera_id');
    // }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
