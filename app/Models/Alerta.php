<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';

    protected $fillable = [
        'user_id',
        'tipo',
        'titulo',
        'mensaje',
        'url',
        'leida',
    ];

    protected $casts = [
        'leida' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function crear(int $userId, string $tipo, string $titulo, string $mensaje, ?string $url = null): self
    {
        return self::create([
            'user_id' => $userId,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'mensaje' => $mensaje,
            'url' => $url,
            'leida' => false,
        ]);
    }

    public static function crearParaTodos(string $tipo, string $titulo, string $mensaje, ?string $url = null): void
    {
        $users = User::all();
        foreach ($users as $user) {
            self::crear($user->id, $tipo, $titulo, $mensaje, $url);
        }
    }
}
