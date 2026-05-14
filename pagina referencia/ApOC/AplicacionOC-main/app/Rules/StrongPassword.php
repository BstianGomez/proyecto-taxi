<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Validar que la contraseña sea lo suficientemente fuerte
     *
     * Requisitos:
     * - Mínimo 12 caracteres
     * - Al menos 1 mayúscula
     * - Al menos 1 minúscula
     * - Al menos 1 número
     * - Al menos 1 carácter especial (!@#$%^&*)
     * - NO contenga el nombre del usuario o email
     * - NO esté en lista de contraseñas comunes
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verificar longitud mínima
        if (strlen($value) < 12) {
            $fail('La contraseña debe tener al menos 12 caracteres.');

            return;
        }

        // Verificar mayúscula
        if (! preg_match('/[A-Z]/', $value)) {
            $fail('La contraseña debe contener al menos una letra mayúscula.');

            return;
        }

        // Verificar minúscula
        if (! preg_match('/[a-z]/', $value)) {
            $fail('La contraseña debe contener al menos una letra minúscula.');

            return;
        }

        // Verificar número
        if (! preg_match('/[0-9]/', $value)) {
            $fail('La contraseña debe contener al menos un número.');

            return;
        }

        // Verificar carácter especial
        if (! preg_match('/[!@#$%^&*()_+\-=\[\]{};:"\'\\|,.<>\/?]/', $value)) {
            $fail('La contraseña debe contener al menos un carácter especial (!@#$%^&*).');

            return;
        }

        // Verificar que no contenga el email/nombre del usuario
        $email = request()->input('email') ?: auth()->user()?->email ?: '';
        $name = request()->input('name') ?: auth()->user()?->name ?: '';

        if ($email && stripos($value, explode('@', $email)[0]) !== false) {
            $fail('La contraseña no puede contener partes de tu email.');

            return;
        }

        if ($name && stripos($value, $name) !== false) {
            $fail('La contraseña no puede contener tu nombre.');

            return;
        }

        // Verificar contra lista de contraseñas comunes
        $commonPasswords = [
            'password', 'password123', '123456', 'qwerty', 'abc123',
            'password@123', 'password!', 'admin', 'letmein', 'welcome',
            'welcome123', 'passwd', 'passw0rd', 'p@ssw0rd', '12345678',
            'password123!', 'password@2024', 'password@2025', 'password@2026',
        ];

        foreach ($commonPasswords as $common) {
            if (strtolower($value) === $common || preg_match('/'.preg_quote($common, '/').'/i', $value)) {
                $fail('La contraseña es demasiado común. Elige una más segura.');

                return;
            }
        }
    }
}
