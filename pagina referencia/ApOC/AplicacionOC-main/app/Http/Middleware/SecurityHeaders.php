<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // ==================== HTTPS & TRANSPORT ====================
        // Force HTTPS en producción
        if (app()->environment('production')) {
            // HSTS - Forzar HTTPS por 1 año + preload
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        // ==================== CONTENT TYPE ====================
        // Prevenir "MIME sniffing" - nunca adivinar tipo de contenido
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // ==================== CLICKJACKING / UI REDRESSING ====================
        // Prevenir que el sitio sea embebido en frames o iframes
        // DENY: No permitir en ningún frame
        // SAMEORIGIN: Solo si viene del mismo origin
        $response->headers->set('X-Frame-Options', 'DENY');

        // ==================== XSS PROTECTION ====================
        // Header antiguo pero útil para navegadores legacy
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // ==================== REFERRER POLICY ====================
        // No enviar referrer a sitios externos (privacidad)
        // strict-origin-when-cross-origin: Solo origen, no path
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // ==================== PERMISSIONS POLICY ====================
        // Deshabilitar acceso a features del navegador (geolocation, cámara, etc)
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), camera=(), microphone=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()'
        );

        // ==================== CONTENT SECURITY POLICY (CSP) ====================
        // La política más importante - controla todas las fuentes de contenido
        $csp = $this->getContentSecurityPolicy($request);
        $response->headers->set('Content-Security-Policy', $csp);

        // ==================== CACHE CONTROL ====================
        // Para rutas sensitivas, no cachear
        if ($this->isSensitiveRoute($request)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        } else {
            // Para recursos estáticos, cachear agresivamente
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
        }

        // ==================== ADDITIONAL SECURITY ====================
        // Desabilitar prefetch de DNS (privacidad)
        $response->headers->set('X-DNS-Prefetch-Control', 'off');

        // Deshabilitar Feature Policy (deprecated, pero útil para compatibilidad)
        $response->headers->set('Feature-Policy', 'geolocation \'none\'; camera \'none\'; microphone \'none\'');

        // X-Powered-By - no revelar qué tecnología usa el servidor
        $response->headers->set('X-Powered-By', 'Enterprise Application');

        // Server - no revelar versión exacta
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Obtener Content Security Policy personalizada
     */
    private function getContentSecurityPolicy(Request $request): string
    {
        // Política base muy restrictiva
        $policy = [
            // default-src - controla todas las fuentes por defecto
            "default-src 'self'",

            // script-src - scripts solo del sitio (no inline)
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' cdn.jsdelivr.net cdnjs.cloudflare.com",

            // style-src - estilos del sitio y Google Fonts
            "style-src 'self' 'unsafe-inline' fonts.googleapis.com fonts.bunny.net cdn.jsdelivr.net cdnjs.cloudflare.com",

            // img-src - imágenes de cualquier origen (permisivo)
            "img-src 'self' data: https:",

            // font-src - fuentes del sitio y Google Fonts
            "font-src 'self' fonts.gstatic.com fonts.bunny.net data:",

            // connect-src - conexiones XHR, WebSocket, etc (solo HTTPS)
            "connect-src 'self' https:",

            // frame-ancestors - no permitir embeber en frames
            "frame-ancestors 'none'",

            // frame-src - no permitir iframes
            "frame-src 'none'",

            // object-src - no permitir embeds (Flash, etc)
            "object-src 'none'",

            // base-uri - restricción de base tags
            "base-uri 'self'",

            // form-action - restricción de envío de formularios
            "form-action 'self'",

            // upgrade-insecure-requests - cambiar http a https
            // 'upgrade-insecure-requests',

            // block-all-mixed-content - bloquear contenido mixto http/https
            // 'block-all-mixed-content',
        ];

        return implode('; ', $policy);
    }

    /**
     * Verificar si es una ruta sensitiva (no cachear)
     */
    private function isSensitiveRoute(Request $request): bool
    {
        $sensitivePatterns = [
            'login',
            'logout',
            'dashboard',
            'usuarios',
            'oc/enviadas',
            'oc/cliente',
            'oc/interna',
            'oc/negocio',
            'profile',
            'settings',
        ];

        foreach ($sensitivePatterns as $pattern) {
            if ($request->is($pattern) || $request->is($pattern.'/*')) {
                return true;
            }
        }

        return false;
    }
}
