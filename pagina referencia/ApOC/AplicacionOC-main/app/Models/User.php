<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_changed_at' => 'datetime',  // Convertir a Carbon
            'last_login_at' => 'datetime',           // Convertir a Carbon
            'last_failed_login_at' => 'datetime',    // Convertir a Carbon
            'account_locked_until' => 'datetime',    // Convertir a Carbon
            'password' => 'hashed',
        ];
    }

    // Helper methods for role checking
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->isSuperAdmin();
    }

    public function isGestor(): bool
    {
        return $this->role === 'gestor' || $this->isAdmin();
    }

    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }

    // ==================== SECURITY METHODS ====================

    /**
     * Verificar si la cuenta está bloqueada por demasiados intentos fallidos
     */
    public function isAccountLocked(): bool
    {
        if (! $this->account_locked_until) {
            return false;
        }

        if (now()->isAfter($this->account_locked_until)) {
            // El bloqueo ha expirado
            $this->update([
                'account_locked_until' => null,
                'failed_login_attempts' => 0,
            ]);

            return false;
        }

        return true;
    }

    /**
     * Incrementar contador de intentos fallidos de login
     */
    public function recordFailedLogin(string $ip, string $userAgent): void
    {
        $failedAttempts = ($this->failed_login_attempts ?? 0) + 1;

        $data = [
            'failed_login_attempts' => $failedAttempts,
            'last_failed_login_at' => now(),
            'last_login_ip' => $ip,
            'last_login_user_agent' => $userAgent,
        ];

        // Bloquear cuenta después de 5 intentos fallidos por 30 minutos
        if ($failedAttempts >= 5) {
            $data['account_locked_until'] = now()->addMinutes(30);
        }

        $this->update($data);
    }

    /**
     * Registrar login exitoso
     */
    public function recordSuccessfulLogin(string $ip, string $userAgent): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'last_failed_login_at' => null,
            'account_locked_until' => null,
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'last_login_user_agent' => $userAgent,
            'must_change_password' => false, // Reset flag
        ]);
    }

    /**
     * Verificar si el usuario tiene 2FA habilitado (preparado para futuro)
     */
    public function hasTwoFactorEnabled(): bool
    {
        // Preparado para implementar 2FA en el futuro
        // return $this->two_factor_secret !== null;
        return false;
    }

    /**
     * Verificar si necesita cambiar contraseña
     */
    public function mustChangePassword(): bool
    {
        return $this->must_change_password ?? false;
    }

    /**
     * Obtener días desde el último cambio de contraseña
     */
    public function daysSincePasswordChange(): ?int
    {
        if (! $this->password_changed_at) {
            return null;
        }

        try {
            return $this->password_changed_at->diffInDays(now());
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Verificar si la contraseña debe ser cambiada por expiración (90 días)
     */
    public function passwordExpired(): bool
    {
        if (! $this->password_changed_at) {
            return true; // Forzar cambio si nunca se ha cambiado
        }

        // Contraseña expira después de 90 días
        try {
            return $this->password_changed_at->addDays(90)->isPast();
        } catch (\Exception $e) {
            // Si hay error al procesar la fecha, no forzar cambio
            return false;
        }
    }
}
