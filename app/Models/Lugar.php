<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lugar extends Model
{
    use HasFactory, SoftDeletes; // SoftDeletes opcional, para eliminación suave

    /**
     * The table associated with the model.
     */
    protected $table = 'lugares';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'direccion',
        'embed_mapa',
        'status',
        'latitud',
        'longitud',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'status' => 'string',
        'latitud' => 'float',
        'longitud' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Indica si el lugar tiene coordenadas para mostrar en el mapa.
     */
    public function tieneCoordenadas(): bool
    {
        return $this->latitud !== null && $this->longitud !== null;
    }

    /**
     * Scopes para consultas comunes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('codigo', 'LIKE', "%{$term}%")
            ->orWhere('nombre', 'LIKE', "%{$term}%")
            ->orWhere('direccion', 'LIKE', "%{$term}%");
    }

    public function getStatusBadgeAttribute()
    {
        return $this->status === 'active'
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-danger">Inactivo</span>';
    }

    public function setCodigoAttribute($value)
    {
        $this->attributes['codigo'] = strtoupper(trim($value));
    }
}
