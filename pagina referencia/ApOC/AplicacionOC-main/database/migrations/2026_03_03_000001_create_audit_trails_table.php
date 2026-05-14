<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Crear tabla para auditoría
     */
    public function up(): void
    {
        // Solo crear si no existe
        if (!Schema::hasTable('audit_trails')) {
            Schema::create('audit_trails', function (Blueprint $table) {
                $table->id();
                
                // Información de timing
                $table->timestamp('timestamp')->index();
                
                // Acción realizada
                $table->string('action');
                
                // Usuario
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('user_email', 255)->nullable();
                $table->string('user_role', 50)->nullable();
                
                // Contexto de la solicitud
                $table->string('ip_address', 45);
                $table->string('method', 10);
                $table->text('url')->nullable();
                $table->text('user_agent')->nullable();
                
                // Nivel de severidad: info, warning, critical
                $table->enum('severity', ['info', 'warning', 'critical'])->default('info')->index();
                
                // Datos adicionales (JSON)
                $table->json('data')->nullable();
                
                // Status: success, failed, blocked
                $table->string('status', 20)->default('success')->index();
                
                // Índices para búsquedas rápidas
                $table->index(['user_id', 'timestamp']);
                $table->index(['action', 'severity', 'timestamp']);
                $table->index(['ip_address', 'timestamp']);
            });
        }
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_trails');
    }
};
