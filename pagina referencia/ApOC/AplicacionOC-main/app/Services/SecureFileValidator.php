<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SecureFileValidator
{
    /**
     * Extensiones permitidas y sus magic bytes correspondientes
     * Magic bytes son los primeros bytes del archivo que identifican el tipo real
     */
    private const ALLOWED_TYPES = [
        'pdf' => [
            'mime' => ['application/pdf'],
            'magic' => ['25504446'], // %PDF
            'max_size' => 10485760, // 10MB
        ],
        'doc' => [
            'mime' => ['application/msword'],
            'magic' => ['D0CF11E0A1B11AE1'], // Old Word format
            'max_size' => 10485760,
        ],
        'docx' => [
            'mime' => [
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/zip', // DOCX es un ZIP
            ],
            'magic' => ['504B0304'], // PK (ZIP header)
            'max_size' => 10485760,
        ],
        'xls' => [
            'mime' => ['application/vnd.ms-excel'],
            'magic' => ['D0CF11E0A1B11AE1'], // Old Excel format
            'max_size' => 10485760,
        ],
        'xlsx' => [
            'mime' => [
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/zip', // XLSX es un ZIP
            ],
            'magic' => ['504B0304'], // PK (ZIP header)
            'max_size' => 10485760,
        ],
    ];

    /**
     * Magic bytes que indican archivos potencialmente peligrosos
     */
    private const DANGEROUS_SIGNATURES = [
        '4D5A' => 'PE Executable (EXE, DLL, SCR)',
        '7F454C46' => 'ELF Executable (Linux)',
        'CAFEBABE' => 'Java Class',
        '3C3F706870' => 'PHP Script',
        '23212F' => 'Script (#!/)',
        '4D534346' => 'Cabinet Archive (CAB)',
    ];

    /**
     * Validar completamente un archivo subido
     *
     * @throws ValidationException
     */
    public function validate(UploadedFile $file): array
    {
        $errors = [];

        // 1. Validar que el archivo existe
        if (! $file->isValid()) {
            throw ValidationException::withMessages([
                'archivo' => 'El archivo no se subió correctamente. Intente nuevamente.',
            ]);
        }

        // 2. Validar tamaño
        if (! $this->validateSize($file)) {
            $errors[] = 'El archivo excede el tamaño máximo permitido de 10MB.';
        }

        // 3. Obtener extensión
        $extension = strtolower($file->getClientOriginalExtension());

        if (! isset(self::ALLOWED_TYPES[$extension])) {
            $errors[] = 'Tipo de archivo no permitido. Solo se aceptan: PDF, DOC, DOCX, XLS, XLSX.';
        }

        // 4. Validar MIME type declarado
        if (! empty($extension) && isset(self::ALLOWED_TYPES[$extension])) {
            if (! $this->validateMimeType($file, $extension)) {
                $errors[] = 'El tipo MIME del archivo no coincide con su extensión.';
            }
        }

        // 5. Validar magic bytes (firma real del archivo)
        if (! $this->validateMagicBytes($file, $extension)) {
            $errors[] = 'El contenido del archivo no coincide con su extensión. Posible archivo corrupto o modificado.';
        }

        // 6. Detectar archivos ejecutables disfrazados
        if ($this->isDangerousFile($file)) {
            Log::warning('Intento de subir archivo peligroso', [
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);

            $errors[] = 'El archivo contiene contenido potencialmente peligroso y ha sido rechazado.';
        }

        // 7. Validar nombre del archivo
        if (! $this->validateFilename($file)) {
            $errors[] = 'El nombre del archivo contiene caracteres no permitidos.';
        }

        // 8. Validar contenido interno para ZIPs (DOCX, XLSX - pueden contener macros)
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, ['docx', 'xlsx'])) {
            $zipValidation = $this->validateZipContents($file);
            if ($zipValidation !== true) {
                $errors[] = $zipValidation;
            }
        }

        if (! empty($errors)) {
            throw ValidationException::withMessages([
                'adjunto' => $errors,
            ]);
        }

        return [
            'safe_filename' => $this->sanitizeFilename($file),
            'extension' => $extension,
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
        ];
    }

    /**
     * Validar tamaño del archivo
     */
    private function validateSize(UploadedFile $file): bool
    {
        // Máximo 10MB
        return $file->getSize() <= 10485760;
    }

    /**
     * Validar MIME type
     */
    private function validateMimeType(UploadedFile $file, string $extension): bool
    {
        if (! isset(self::ALLOWED_TYPES[$extension])) {
            return false;
        }

        $allowedMimes = self::ALLOWED_TYPES[$extension]['mime'];
        $fileMime = $file->getMimeType();

        return in_array($fileMime, $allowedMimes, true);
    }

    /**
     * Validar magic bytes del archivo
     */
    private function validateMagicBytes(UploadedFile $file, string $extension): bool
    {
        if (! isset(self::ALLOWED_TYPES[$extension])) {
            return false;
        }

        try {
            $handle = fopen($file->getRealPath(), 'rb');
            if (! $handle) {
                return false;
            }

            // Leer los primeros 8 bytes
            $header = fread($handle, 8);
            fclose($handle);

            $headerHex = strtoupper(bin2hex($header));
            $allowedMagic = self::ALLOWED_TYPES[$extension]['magic'];

            foreach ($allowedMagic as $magic) {
                if (str_starts_with($headerHex, strtoupper($magic))) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error validando magic bytes', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName(),
            ]);

            return false;
        }
    }

    /**
     * Detectar archivos peligrosos
     */
    private function isDangerousFile(UploadedFile $file): bool
    {
        try {
            $handle = fopen($file->getRealPath(), 'rb');
            if (! $handle) {
                return true; // Si no podemos leer, rechazamos por seguridad
            }

            // Leer primeros bytes para detectar ejecutables
            $header = fread($handle, 10);
            fclose($handle);

            $headerHex = strtoupper(bin2hex($header));

            foreach (self::DANGEROUS_SIGNATURES as $signature => $type) {
                if (str_starts_with($headerHex, $signature)) {
                    Log::warning("Archivo peligroso detectado: {$type}", [
                        'filename' => $file->getClientOriginalName(),
                        'signature' => $signature,
                    ]);

                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('Error detectando archivo peligroso', [
                'error' => $e->getMessage(),
            ]);

            return true; // Rechazar si hay error
        }
    }

    /**
     * Validar contenido interno de archivos ZIP (DOCX, XLSX)
     * Busca archivos ejecutables u otros tipos peligrosos dentro del ZIP
     */
    private function validateZipContents(UploadedFile $file)
    {
        try {
            $zip = new \ZipArchive;
            $result = $zip->open($file->getRealPath());

            if ($result !== true) {
                return 'El archivo ZIP está corrupto o no puede abrirse.';
            }

            // Extensiones peligrosas que pueden estar dentro del ZIP
            $dangerousExtensions = [
                'exe' => 'Ejecutable',
                'dll' => 'Librería ejecutable',
                'vbs' => 'Script VBScript',
                'js' => 'Script JavaScript',
                'cmd' => 'Script CMD',
                'bat' => 'Script Batch',
                'sh' => 'Script Shell',
                'ps1' => 'PowerShell script',
                'jar' => 'Java Archive',
                'app' => 'Mac Application',
                'bin' => 'Binary',
                'deb' => 'Debian Package',
                'rpm' => 'RPM Package',
                'msi' => 'Windows Installer',
                'scr' => 'Screen Saver',
                'pif' => 'Program Info File',
                'com' => 'DOS Executable',
            ];

            // Buscar patrones de macro en XML (para DOCX/XLSX)
            $macroPatterns = [
                'vbaProject.bin', // VBA macros
                'macrosheets.xml',
                'connections.xml',
                'customXml/',
                'activeX',
            ];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

                // Verificar extensiones peligrosas
                if (isset($dangerousExtensions[$ext])) {
                    $zip->close();
                    $type = $dangerousExtensions[$ext];
                    Log::warning("Archivo peligroso detectado dentro del ZIP: {$type}", [
                        'filename' => $file->getClientOriginalName(),
                        'internal_file' => $filename,
                    ]);

                    return "El archivo contiene {$type} interno ($filename) no permitido.";
                }

                // Verificar patrones de macros y contenido potencialmente peligroso
                $lowerFilename = strtolower($filename);
                foreach ($macroPatterns as $pattern) {
                    if (stripos($lowerFilename, $pattern) !== false) {
                        // Log pero permitir (las macros se deshabilitan al abrir en Office)
                        Log::info('Documento con macros potencial detectado', [
                            'filename' => $file->getClientOriginalName(),
                            'pattern' => $pattern,
                        ]);
                        // Not rejecting, pero alertar
                    }
                }
            }

            $zip->close();

            return true;
        } catch (\Exception $e) {
            Log::error('Error validando contenido del ZIP', [
                'filename' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
            ]);

            return 'Error al validar el contenido del archivo. Por favor, intente con otro archivo.';
        }
    }

    /**
     * Validar nombre del archivo
     */
    private function validateFilename(UploadedFile $file): bool
    {
        $filename = $file->getClientOriginalName();

        // Rechazar nombres con caracteres peligrosos
        $dangerousPatterns = [
            '/\.\./', // Path traversal
            '/[<>:"|?*]/', // Caracteres especiales de Windows
            '/[\x00-\x1F]/', // Caracteres de control
            '/\.exe$/i', // Ejecutables
            '/\.bat$/i',
            '/\.cmd$/i',
            '/\.sh$/i',
            '/\.ps1$/i',
            '/\.vbs$/i',
            '/\.jar$/i',
            '/\.app$/i',
            '/\.deb$/i',
            '/\.rpm$/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $filename)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sanitizar nombre del archivo
     */
    public function sanitizeFilename(UploadedFile $file): string
    {
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();

        // Eliminar extensión
        $name = pathinfo($filename, PATHINFO_FILENAME);

        // Remover caracteres especiales y espacios
        $name = preg_replace('/[^a-zA-Z0-9_-]/', '_', $name);

        // Limitar longitud
        $name = substr($name, 0, 50);

        // Generar nombre único con timestamp
        $safeName = $name.'_'.time().'_'.uniqid();

        return $safeName.'.'.strtolower($extension);
    }

    /**
     * Obtener extensiones permitidas
     */
    public static function getAllowedExtensions(): array
    {
        return array_keys(self::ALLOWED_TYPES);
    }

    /**
     * Obtener MIME types permitidos
     */
    public static function getAllowedMimes(): array
    {
        $mimes = [];
        foreach (self::ALLOWED_TYPES as $type) {
            $mimes = array_merge($mimes, $type['mime']);
        }

        return array_unique($mimes);
    }
}
