<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Agregar campos de seguridad a tabla users
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Campos existentes ya están, agregar nuevos para seguridad
            
            // Intentos fallidos de login
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->nullable();
            
            // Timestamp del último intento fallido
            $table->timestamp('last_failed_login_at')->nullable();
            
            // Bloqueo de cuenta (por demasiados intentos)
            $table->timestamp('account_locked_until')->nullable();
            
            // IP del último login exitoso
            $table->string('last_login_ip')->nullable();
            
            // Timestamp del último login
            $table->timestamp('last_login_at')->nullable();
            
            // UserAgent del último login
            $table->text('last_login_user_agent')->nullable();
            
            // Verificación de email
            $table->timestamp('email_verified_at')->nullable()->change();
            
            // Cambio de password (para obligar cambio después de reset)
            $table->timestamp('password_changed_at')->nullable();
            
            // Administrador puede forzar cambio de password
            $table->boolean('must_change_password')->default(false);
            
            // Índices para búsquedas rápidas
            $table->index('failed_login_attempts');
            $table->index('account_locked_until');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['failed_login_attempts']);
            $table->dropIndex(['account_locked_until']);
            $table->dropIndex(['last_login_at']);
            
            $table->dropColumn([
                'failed_login_attempts',
                'last_failed_login_at',
                'account_locked_until',
                'last_login_ip',
                'last_login_at',
                'last_login_user_agent',
                'password_changed_at',
                'must_change_password',
            ]);
        });
    }
};
