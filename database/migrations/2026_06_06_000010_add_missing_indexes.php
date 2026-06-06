<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Indice compuesto para la consulta de alertas no leidas por usuario.
 *
 * La campana de notificaciones consulta en cada request las alertas no leidas
 * del usuario autenticado (WHERE user_id = ? AND leida = 0). El indice
 * (user_id, leida) cubre exactamente ese patron. Las demas FK (user_id,
 * carrera_id, preinscripcion_id, rol_id) ya tienen indice automatico por ser
 * claves foraneas, por lo que no se agregan aqui.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->index(['user_id', 'leida'], 'idx_alertas_user_leida');
        });
    }

    public function down(): void
    {
        Schema::table('alertas', function (Blueprint $table) {
            $table->dropIndex('idx_alertas_user_leida');
        });
    }
};
