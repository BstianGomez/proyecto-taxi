<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuditService
{
    /**
     * Registrar acción en log de auditoría con contexto completo
     */
    public static function log(string $action, array $data = []): void
    {
        $user = Auth::user();
        $request = request();

        $auditData = [
            'timestamp' => now()->toIso8601String(),
            'action' => $action,
            'user_id' => $user?->id,
            'user_email' => $user?->email ?? 'anonymous',
            'user_role' => $user?->role ?? 'guest',
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
            'method' => $request->method(),
            'url' => $request->getRequestUri(),
            'severity' => $data['severity'] ?? 'info',
            ...array_filter($data, fn ($v) => ! is_null($v) && $v !== ''),
        ];

        // Log a archivo de auditoría
        Log::channel('audit')->log(
            $auditData['severity'],
            $action,
            $auditData
        );

        // En producción, también guardar en BD si es crítico
        if (app()->environment('production') && in_array($auditData['severity'], ['critical', 'warning'])) {
            try {
                DB::table('audit_trails')->insert($auditData);
            } catch (\Exception $e) {
                Log::error('Failed to insert audit trail', [
                    'action' => $action,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Registrar intento de login exitoso
     */
    public static function logLogin(string $email, array $extra = []): void
    {
        self::log('LOGIN_SUCCESS', [
            'email' => strtolower($email),
            'severity' => 'info',
            ...$extra,
        ]);
    }

    /**
     * Registrar intento de login fallido
     */
    public static function logFailedLogin(string $email, array $extra = []): void
    {
        self::log('LOGIN_FAILED', [
            'email' => strtolower($email),
            'severity' => 'warning',
            ...$extra,
        ]);
    }

    /**
     * Registrar acceso no autorizado
     */
    public static function logUnauthorized(string $route, array $extra = []): void
    {
        self::log('UNAUTHORIZED_ACCESS', [
            'route' => $route,
            'severity' => 'warning',
            ...$extra,
        ]);
    }

    /**
     * Registrar cambio de datos sensible
     */
    public static function logDataChange(string $resource, string $action, array $changes = [], string $severity = 'info'): void
    {
        // Filtrar campos sensibles del log
        $allowedFields = [
            'name', 'email', 'role', 'status', 'estado', 'cantidad', 'monto',
            'descripcion', 'tipo_solicitud', 'nombre_proveedor',
        ];

        $filteredChanges = [];
        foreach ($changes as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $filteredChanges[$key] = is_string($value) ? substr($value, 0, 255) : $value;
            }
        }

        self::log('DATA_CHANGE', [
            'resource' => $resource,
            'resource_action' => $action,
            'changes' => count($filteredChanges) > 0 ? $filteredChanges : null,
            'severity' => $severity,
        ]);
    }

    /**
     * Registrar intento de subida de archivo peligroso
     */
    public static function logDangerousFileAttempt(string $filename, string $reason, array $extra = []): void
    {
        self::log('DANGEROUS_FILE_ATTEMPT', [
            'filename' => $filename,
            'reason' => $reason,
            'severity' => 'critical',
            ...$extra,
        ]);
    }

    /**
     * Registrar descarga de archivo
     */
    public static function logFileDownload(string $filename, int $size, array $extra = []): void
    {
        self::log('FILE_DOWNLOADED', [
            'filename' => substr($filename, 0, 255),
            'size_bytes' => $size,
            'severity' => 'info',
            ...$extra,
        ]);
    }

    /**
     * Registrar cambio de password
     */
    public static function logPasswordChange(array $extra = []): void
    {
        self::log('PASSWORD_CHANGED', [
            'severity' => 'info',
            ...$extra,
        ]);
    }

    /**
     * Registrar cambios de configuración o políticas
     */
    public static function logPolicyChange(string $policy, array $changes, array $extra = []): void
    {
        self::log('POLICY_CHANGED', [
            'policy' => $policy,
            'changes' => $changes,
            'severity' => 'warning',
            ...$extra,
        ]);
    }

    /**
     * Registrar acceso a datos sensibles
     */
    public static function logSensitiveDataAccess(string $dataType, array $extra = []): void
    {
        self::log('SENSITIVE_DATA_ACCESS', [
            'data_type' => $dataType,
            'severity' => 'info',
            ...$extra,
        ]);
    }

    /**
     * Registrar intento de SQL injection o ataque
     */
    public static function logSecurityThreat(string $threatType, string $details, array $extra = []): void
    {
        self::log('SECURITY_THREAT', [
            'threat_type' => $threatType,
            'details' => substr($details, 0, 500),
            'severity' => 'critical',
            ...$extra,
        ]);
    }
}
