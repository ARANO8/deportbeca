<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $table = 'disciplines';

    protected $fillable = [
        'codigo', 'nombre', 'descripcion', 'parent_id', 'status',
    ];

    // Relación para obtener las subdisciplinas (hijos)
    public function children()
    {
        return $this->hasMany(Discipline::class, 'parent_id');
    }

    // Relación para obtener la disciplina padre (usando nombre diferente)
    public function disciplinaPadre()
    {
        return $this->belongsTo(Discipline::class, 'parent_id');
    }

    // Alias para subDisciplines (compatibilidad con tu ArchivadorController)
    public function subDisciplines()
    {
        return $this->hasMany(Discipline::class, 'parent_id');
    }

    // Scope para disciplinas principales
    public function scopeMainDisciplines($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope para disciplinas activas
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
