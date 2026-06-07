<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    use HasFactory;

    protected $table = 'disciplines';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'parent_id',
        'status',
        'ubicacion_mapa', // Agregado el campo del mapa
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

    // Eventos que tienen habilitada esta disciplina (via tabla junction)
    public function eventos()
    {
        return $this->belongsToMany(
            EventoConfiguracion::class,
            'evento_configuracion_disciplinas',
            'discipline_id',
            'evento_configuracion_id'
        );
    }

    // ========== NUEVOS MÉTODOS PARA EL MAPA ==========

    /**
     * Verifica si la disciplina tiene un mapa configurado
     */
    public function tieneMapa()
    {
        return ! empty($this->ubicacion_mapa);
    }

    /**
     * Obtiene la URL del mapa embed
     */
    public function getEmbedMapUrl()
    {
        if ($this->tieneMapa()) {
            return $this->ubicacion_mapa;
        }

        return null;
    }

    /**
     * Obtiene el HTML del mapa embed
     */
    public function getMapaEmbedHtml($width = '100%', $height = 350)
    {
        if (! $this->tieneMapa()) {
            return '<div class="alert alert-warning">No hay mapa configurado para esta disciplina.</div>';
        }

        return '<iframe src="'.$this->ubicacion_mapa.'" 
                        width="'.$width.'" 
                        height="'.$height.'" 
                        style="border:0; border-radius: 10px;" 
                        allowfullscreen="" 
                        loading="lazy">
                </iframe>';
    }

    /**
     * Obtiene el nombre completo de la disciplina (incluyendo padre si existe)
     */
    public function getNombreCompletoAttribute()
    {
        if ($this->disciplinaPadre) {
            return $this->disciplinaPadre->nombre.' - '.$this->nombre;
        }

        return $this->nombre;
    }

    /**
     * Obtiene todas las disciplinas (incluyendo padres e hijos) para select
     */
    public static function getForSelect()
    {
        $disciplinas = self::with('disciplinaPadre')->get();
        $options = [];

        foreach ($disciplinas as $disciplina) {
            if ($disciplina->parent_id === null) {
                $options[$disciplina->id] = $disciplina->nombre;
                foreach ($disciplinas as $sub) {
                    if ($sub->parent_id === $disciplina->id) {
                        $options[$sub->id] = '  └─ '.$sub->nombre;
                    }
                }
            }
        }

        return $options;
    }
}
