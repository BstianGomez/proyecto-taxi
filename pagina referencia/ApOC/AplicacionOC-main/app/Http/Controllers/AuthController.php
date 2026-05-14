<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLogin()
    {
        // Redirigir si ya está autenticado
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('usuario')) {
                return redirect()->route('oc.user.home');
            }

            if ($user->isCliente()) {
                return redirect()->route('oc.home');
            }

            return redirect()->route('oc.index');
        }

        return view('auth.login');
    }

    /**
     * Procesar login con seguridad avanzada
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $email = strtolower($credentials['email']);
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // 1. Encontrar usuario por email
        $user = User::where('email', $email)->first();

        if (! $user) {
            // No revelar si el usuario existe (seguridad)
            Log::warning('Login attempt with non-existent email', [
                'email' => $email,
                'ip' => $ip,
                'timestamp' => now(),
            ]);

            AuditService::log('LOGIN_FAILED_USER_NOT_FOUND', [
                'email' => $email,
                'ip' => $ip,
            ]);

            return back()->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])->onlyInput('email');
        }

        // 2. Verificar si la cuenta está bloqueada
        if ($user->isAccountLocked()) {
            Log::warning('Login attempt on locked account', [
                'email' => $email,
                'ip' => $ip,
                'locked_until' => $user->account_locked_until,
            ]);

            AuditService::log('LOGIN_FAILED_ACCOUNT_LOCKED', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $ip,
                'locked_until' => $user->account_locked_until,
            ]);

            $minutesRemaining = $user->account_locked_until->diffInMinutes(now());

            return back()->withErrors([
                'email' => "Tu cuenta está bloqueada por demasiados intentos fallidos. Intenta en {$minutesRemaining} minutos.",
            ])->onlyInput('email');
        }

        // 3. Verificar si el usuario está activo
        if (! $user->is_active ?? true) {
            Log::warning('Login attempt on inactive account', [
                'email' => $email,
                'ip' => $ip,
            ]);

            AuditService::log('LOGIN_FAILED_ACCOUNT_INACTIVE', [
                'user_id' => $user->id,
                'email' => $email,
                'ip' => $ip,
            ]);

            return back()->withErrors([
                'email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.',
            ])->onlyInput('email');
        }

        // 4. Verificar credenciales
        if (! Auth::attempt(['email' => $email, 'password' => $credentials['password']], remember: false)) {
            // Login fallido: registrar intento y incrementar contador
            $user->recordFailedLogin($ip, $userAgent);

            Log::warning('Failed login attempt', [
                'email' => $email,
                'ip' => $ip,
                'user_id' => $user->id,
                'attempts' => $user->failed_login_attempts,
            ]);

            AuditService::logFailedLogin($email, [
                'user_id' => $user->id,
                'ip' => $ip,
                'attempts' => $user->failed_login_attempts,
            ]);

            // Mensaje genérico para no revelar si es contraseña incorrecta
            return back()->withErrors([
                'email' => 'Las credenciales no son válidas.',
            ])->onlyInput('email');
        }

        // 5. LOGIN EXITOSO - Regenerar sesión y registrar
        $request->session()->regenerate();

        // Registrar login exitoso
        $user->recordSuccessfulLogin($ip, $userAgent);

        Log::info('User logged in successfully', [
            'email' => $email,
            'user_id' => $user->id,
            'ip' => $ip,
        ]);

        AuditService::logLogin($email, [
            'user_id' => $user->id,
            'ip' => $ip,
            'user_agent' => substr($userAgent, 0, 255),
        ]);

        // 6. Verificar si debe cambiar contraseña (DESACTIVADO por solicitud del usuario)
        /*
        if ($user->passwordExpired() || $user->mustChangePassword()) {
            return redirect()->route('password.change')
                ->with('warning', 'Debes cambiar tu contraseña antes de continuar.');
        }
        */

        // 7. Redirigir según rol
        if ($user->hasRole('usuario')) {
            return redirect()->route('oc.user.home');
        }

        if ($user->isCliente()) {
            return redirect()->route('oc.home');
        }

        return redirect()->route('oc.index');
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        $email = auth()->user()?->email;
        $userId = auth()->id();
        $ip = $request->ip();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Registrar logout
        AuditService::log('LOGOUT', [
            'user_id' => $userId,
            'email' => $email,
            'ip' => $ip,
        ]);

        Log::info('User logged out', [
            'user_id' => $userId,
            'email' => $email,
            'ip' => $ip,
        ]);

        return redirect()->route('login')->with('success', 'Has cerrado sesión exitosamente.');
    }

    /**
     * Página para cambiar contraseña (forzado)
     */
    public function showChangePassword()
    {
        return view('auth.change-password');
    }

    /**
     * Procesar cambio de contraseña obligatorio
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => [
                'required',
                'string',
                'min:12',
                'confirmed',
                'different:current_password',
                new \App\Rules\StrongPassword,
            ],
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.different' => 'La nueva contraseña debe ser diferente a la actual.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'current_password.required' => 'Debes ingresar tu contraseña actual.',
        ]);

        $user = auth()->user();

        // Verificar contraseña actual
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual es incorrecta.']);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'must_change_password' => false,
        ]);

        AuditService::log('PASSWORD_CHANGED', [
            'user_id' => $user->id,
            'ip' => $request->ip(),
        ]);

        if ($user->hasRole('usuario')) {
            return redirect()->route('oc.user.home')
                ->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
        }

        if ($user->isCliente()) {
            return redirect()->route('oc.home')
                ->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
        }

        return redirect()->route('oc.index')
            ->with('success', 'Tu contraseña ha sido actualizada exitosamente.');
    }
}
