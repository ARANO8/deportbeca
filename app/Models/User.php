<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'carnet',
        'telefono',
        'apaterno',
        'amaterno',
        'rol_id',
        'foto',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // 'password' => 'hashed',
    ];

    // Relación con Rol
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    /**
     * Indica si el usuario tiene un rol con acceso total al sistema.
     */
    public function esSuperAdmin(): bool
    {
        return (bool) $this->rol?->es_super_admin;
    }

    /**
     * Devuelve el identificador del menu del panel segun el rol del usuario.
     * Centraliza el mapeo rol -> archivo de menu (includes/panel/menu/{key}.blade.php)
     * para no depender del antiguo campo string 'role'.
     */
    public function menuKey(): string
    {
        if ($this->esSuperAdmin()) {
            return 'admin';
        }

        return match (strtolower($this->rol?->nombre ?? '')) {
            'secretario' => 'secretaria',
            'instructor' => 'profe',
            default => 'profe',
        };
    }

    public function alertas()
    {
        return $this->hasMany(Alerta::class)->orderBy('created_at', 'desc');
    }

    public function alertasNoLeidas()
    {
        return $this->hasMany(Alerta::class)->where('leida', false);
    }
}
