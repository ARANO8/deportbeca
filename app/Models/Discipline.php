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
        'ubicacion_mapa',
        'latitud',
        'longitud',
        'min_integrantes_grupal',
        'max_integrantes_grupal',
        'min_integrantes_individual',
        'max_integrantes_individual',
    ];

    protected $casts = [
        'latitud' => 'float',
        'longitud' => 'float',
        'min_integrantes_grupal' => 'integer',
        'max_integrantes_grupal' => 'integer',
        'min_integrantes_individual' => 'integer',
        'max_integrantes_individual' => 'integer',
    ];

    /**
     * Indica si la disciplina tiene coordenadas para mostrar en el mapa.
     */
    public function tieneCoordenadas(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    /**
     * Indica si la disciplina tiene definido algun limite de integrantes en la
     * modalidad dada ('grupal' | 'individual').
     */
    public function tieneRango(string $modalidad): bool
    {
        return ! is_null($this->{"min_integrantes_{$modalidad}"})
            || ! is_null($this->{"max_integrantes_{$modalidad}"});
    }

    /**
     * Indica si la disciplina admite la modalidad dada. La modalidad se deriva
     * de donde haya rango definido: si no se configuro ningun rango se permiten
     * ambas modalidades (comportamiento por defecto, retrocompatible).
     */
    public function permiteModalidad(string $modalidad): bool
    {
        if (! $this->tieneRango('grupal') && ! $this->tieneRango('individual')) {
            return true;
        }

        return $this->tieneRango($modalidad);
    }

    /**
     * Rango oficial de integrantes [min, max] de la disciplina para la modalidad
     * dada. Cualquiera de los dos puede ser null (min => sin minimo explicito,
     * max => sin tope oficial).
     */
    public function rangoIntegrantes(string $modalidad): array
    {
        return [
            'min' => $this->{"min_integrantes_{$modalidad}"},
            'max' => $this->{"max_integrantes_{$modalidad}"},
        ];
    }

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
