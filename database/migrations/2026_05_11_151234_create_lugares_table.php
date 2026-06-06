<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lugares', function (Blueprint $table) {
            $table->id();                           // ID autoincremental
            $table->string('codigo', 20)->unique(); // Código único
            $table->string('nombre', 150);          // Nombre del lugar
            $table->text('descripcion')->nullable(); // Descripción opcional
            $table->string('direccion', 255);       // Dirección
            $table->text('embed_mapa')->nullable();  // Código embed de mapa
            $table->enum('status', ['active', 'inactive'])->default('active'); // Estado
            $table->timestamps();                    // created_at, updated_at
            $table->softDeletes();                   // deleted_at ← IMPORTANTE!

            // Índices para mejorar rendimiento
            $table->index('codigo');
            $table->index('status');
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lugares');
    }
};
