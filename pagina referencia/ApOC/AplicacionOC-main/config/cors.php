<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración CORS segura para la aplicación
    | Por defecto, CORS está deshabilitado (RESTRICTIVO)
    | Solo habilitar CORS si realmente es necesario
    |
    */

    'paths' => [
        // API paths que pueden recibir requests de otros orígenes
        // Por defecto, vacío = sin CORS
    ],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        // Solo dominio producción
        env('APP_URL', 'http://localhost'),
    ],

    'allowed_origins_patterns' => [
        // Pattern para subdomains si es necesario
        // '/.*\.example\.com/',
    ],

    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-Token',
    ],

    'exposed_headers' => [
        'Content-Range',
        'X-Content-Range',
    ],

    'max_age' => 0,  // No cachear pre-flight requests en producción

    'supports_credentials' => true,  // Permitir cookies en CORS

];
