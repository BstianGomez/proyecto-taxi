<?php

/**
 * AWS Secrets Manager Configuration for Laravel
 *
 * Este archivo permite leer secretos de AWS Secrets Manager
 * en lugar de variables de entorno expuestas (.env)
 *
 * INSTALACIÓN:
 * composer require aws/aws-sdk-php
 *
 * USO:
 * $secret = new AwsSecretsManager();
 * $dbPassword = $secret->getSecret('db-password');
 */

namespace App\Services;

use Aws\Exception\AwsException;
use Aws\SecretsManager\SecretsManagerClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AwsSecretsManager
{
    private SecretsManagerClient $client;

    private string $region;

    private int $cacheTtl = 3600; // 1 hora

    public function __construct()
    {
        $this->region = config('aws.default_region', 'us-east-1');

        // Usar IAM Role desde EC2/ECS - NO credenciales hardcoded
        $this->client = new SecretsManagerClient([
            'version' => 'latest',
            'region' => $this->region,
        ]);
    }

    /**
     * Obtener un secreto de AWS Secrets Manager
     * Cachear por 1 hora para reducir API calls
     *
     * @param  string  $secretName  Nombre del secreto (ej: 'db-password', 'api-key')
     * @param  string  $key  Clave específica del JSON (ej: 'password' en un JSON)
     */
    public function getSecret(string $secretName, ?string $key = null): ?string
    {
        try {
            // Intentar obtener del cache primero
            $cacheKey = "aws-secret:{$secretName}:{$key}";
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            // Obtener de Secrets Manager
            $result = $this->client->getSecretValue([
                'SecretId' => $secretName,
            ]);

            // El secreto puede ser un string o un JSON
            if (isset($result['SecretString'])) {
                $secret = $result['SecretString'];

                // Si es JSON y se pide una clave específica
                if ($key) {
                    $json = json_decode($secret, true);
                    if (isset($json[$key])) {
                        $value = $json[$key];
                        Cache::put($cacheKey, $value, $this->cacheTtl);

                        return $value;
                    }

                    return null;
                }

                // Retornar el string completo
                Cache::put($cacheKey, $secret, $this->cacheTtl);

                return $secret;
            }

            // Si es binario
            if (isset($result['SecretBinary'])) {
                $secret = base64_decode($result['SecretBinary']);
                Cache::put($cacheKey, $secret, $this->cacheTtl);

                return $secret;
            }

            Log::warning("Secret not found or empty: {$secretName}");

            return null;

        } catch (AwsException $e) {
            $errorCode = $e->getAwsErrorCode();

            if ($errorCode === 'ResourceNotFoundException') {
                Log::error("Secret not found in Secrets Manager: {$secretName}");
            } elseif ($errorCode === 'InvalidRequestException') {
                Log::error("Invalid request to Secrets Manager: {$secretName}");
            } elseif ($errorCode === 'InvalidParameterException') {
                Log::error("Invalid parameter for secret: {$secretName}");
            } else {
                Log::error("AWS Secrets Manager error: {$e->getMessage()}");
            }

            // En producción, lanzar excepción; en desarrollo, usar fallback
            if (app()->environment('production')) {
                throw $e;
            }

            // Fallback a .env en desarrollo (NO en producción)
            $envKey = strtoupper(str_replace('-', '_', $secretName));

            return env($envKey);
        }
    }

    /**
     * Obtener todos los secretos de un nombre (ej: base de datos con user + password)
     */
    public function getSecretJson(string $secretName): array
    {
        try {
            $cacheKey = "aws-secret-json:{$secretName}";
            if (Cache::has($cacheKey)) {
                return Cache::get($cacheKey);
            }

            $result = $this->client->getSecretValue([
                'SecretId' => $secretName,
            ]);

            if (isset($result['SecretString'])) {
                $secrets = json_decode($result['SecretString'], true);
                Cache::put($cacheKey, $secrets, $this->cacheTtl);

                return $secrets;
            }

            return [];

        } catch (AwsException $e) {
            Log::error("Error getting secret JSON: {$e->getMessage()}");

            if (app()->environment('production')) {
                throw $e;
            }

            return [];
        }
    }

    /**
     * Invalidar cache de un secreto (cuando se rota)
     */
    public function invalidateCache(string $secretName): void
    {
        $pattern = "aws-secret:{$secretName}:*";
        Cache::forget($pattern);
    }
}
