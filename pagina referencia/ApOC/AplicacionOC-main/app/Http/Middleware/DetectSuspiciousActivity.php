<?php

namespace App\Http\Middleware;

use App\Services\AuditService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DetectSuspiciousActivity
{
    /**
     * Handle an incoming request.
     *
     * Detectar comportamiento sospechoso:
     * - Cambio de IP durante sesión
     * - Múltiples IPs en corto tiempo
     * - User agents diferentes
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = auth()->user()) {
            $currentIp = $request->ip();
            $currentUserAgent = $request->header('User-Agent');

            // 1. Verificar si la IP cambió durante la sesión
            if ($user->last_login_ip && $user->last_login_ip !== $currentIp) {
                Log::warning('User IP changed during session', [
                    'user_id' => $user->id,
                    'previous_ip' => $user->last_login_ip,
                    'current_ip' => $currentIp,
                ]);

                AuditService::log('IP_CHANGE_DETECTED', [
                    'previous_ip' => $user->last_login_ip,
                    'current_ip' => $currentIp,
                    'severity' => 'warning',
                ]);

                // Configurar variable de sesión para alertar al usuario más tarde
                session(['ip_changed_alert' => true]);
            }

            // 2. Verificar si el user agent cambió
            if ($user->last_login_user_agent &&
                substr($user->last_login_user_agent, 0, 50) !== substr($currentUserAgent, 0, 50)) {
                Log::warning('User agent changed during session', [
                    'user_id' => $user->id,
                    'previous_agent' => substr($user->last_login_user_agent, 0, 100),
                    'current_agent' => substr($currentUserAgent, 0, 100),
                ]);

                session(['user_agent_changed_alert' => true]);
            }
        }

        return $next($request);
    }
}
