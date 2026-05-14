<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de Seguridad de Archivos
    |--------------------------------------------------------------------------
    |
    | Configuraciones relacionadas con la validación y manejo seguro de archivos.
    |
    */

    // Tamaño máximo de archivo en bytes (10MB)
    'max_file_size' => 10 * 1024 * 1024,

    // Extensiones de archivo permitidas
    'allowed_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],

    // Extensiones prohibidas (ejecutables, scripts, etc.)
    'forbidden_extensions' => [
        'exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js',
        'jar', 'app', 'deb', 'rpm', 'sh', 'ps1', 'msi', 'dmg',
        'htm', 'html', 'php', 'asp', 'aspx', 'jsp', 'cgi',
        'pl', 'py', 'rb', 'so', 'dll', 'sys', 'drv', 'lnk',
    ],

    // MIME types permitidos
    'allowed_mimes' => [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip', // Para DOCX y XLSX
    ],

    // Firmas de archivo (magic bytes) peligrosas
    'dangerous_signatures' => [
        '4D5A' => 'PE Executable (EXE, DLL, SCR)',
        '7F454C46' => 'ELF Executable (Linux)',
        'CAFEBABE' => 'Java Class',
        '3C3F706870' => 'PHP Script',
        '23212F' => 'Script (#!/)',
        '4D534346' => 'Cabinet Archive (CAB)',
    ],

    // Longitud máxima del nombre de archivo
    'max_filename_length' => 255,

    // Registrar intentos de subir archivos peligrosos
    'log_dangerous_attempts' => true,

    // Directorio de almacenamiento (relativo a storage/app)
    'upload_directory' => 'private/ocs',

    // Generar nombres únicos para archivos
    'use_unique_filenames' => true,

    // Escanear archivos con antivirus (requiere ClamAV instalado)
    'scan_with_antivirus' => env('SCAN_FILES_WITH_ANTIVIRUS', false),

    // Límite de archivos por solicitud
    'max_files_per_request' => 1,
];
