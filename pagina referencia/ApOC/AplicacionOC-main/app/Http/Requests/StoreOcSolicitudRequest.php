<?php

namespace App\Http\Requests;

use App\Rules\SecureFile;
use Illuminate\Foundation\Http\FormRequest;

class StoreOcSolicitudRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'my_company_website' => ['max:0'], // Honeypot (trampa para bots): si tiene más de 0 caracteres, falla.
            'ceco' => ['nullable', 'string', 'max:50'],
            'tipo_solicitud' => ['nullable', 'string', 'in:Interna,Negocio,Cliente'],
            'tipo_documento' => ['nullable', 'string', 'max:100'],
            'rut_proveedor' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{1,8}-[0-9kK]{1}$/'],
            'nombre_proveedor' => ['nullable', 'string', 'max:255'],
            'email_proveedor' => ['nullable', 'email', 'max:255'],
            'descripcion' => ['required', 'string', 'max:5000'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:999999'],
            'cantidad_participantes' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'cantidad_modulos' => ['nullable', 'integer', 'min:1', 'max:999999'],
            'monto' => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
            'observacion' => ['nullable', 'string', 'max:5000'],
            'nombre_curso' => ['nullable', 'string', 'max:255'],
            'adjunto' => ['nullable', 'file', 'max:10240', new SecureFile],
            'cod_cliente' => ['nullable', 'string', 'max:50'],
            'razon_social' => ['nullable', 'string', 'max:255'],
            'rut_razon_social' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]{1,8}-[0-9kK]{1}$/'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'monto.required' => 'El monto es obligatorio.',
            'monto.numeric' => 'El monto debe ser un número válido.',
            'monto.min' => 'El monto debe ser mayor a 0.',
            'monto.max' => 'El monto no puede exceder 999.999.999.999,99.',

            'descripcion.required' => 'La descripción es obligatoria.',
            'descripcion.max' => 'La descripción no puede exceder 5000 caracteres.',

            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.integer' => 'La cantidad debe ser un número entero.',
            'cantidad.min' => 'La cantidad debe ser al menos 1.',
            'cantidad.max' => 'La cantidad no puede exceder 999.999.',

            'rut_proveedor.regex' => 'El RUT del proveedor debe tener el formato 12345678-9.',
            'rut_razon_social.regex' => 'El RUT debe tener el formato 12345678-9.',

            'email_proveedor.email' => 'El email del proveedor no es válido.',

            'adjunto.mimes' => 'El archivo debe ser PDF, Word, Excel o imagen (JPG, PNG).',
            'adjunto.max' => 'El archivo no puede exceder 10MB.',

            'tipo_solicitud.in' => 'El tipo de solicitud debe ser Interna, Negocio o Cliente.',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Sanitizar inputs para prevenir XSS y injection attacks
     */
    protected function prepareForValidation(): void
    {
        // Campos de texto que necesitan sanitización
        $textFields = [
            'descripcion',
            'observacion',
            'nombre_proveedor',
            'nombre_curso',
            'razon_social',
            'tipo_documento',
        ];

        // Sanitizar campos de texto (remover etiquetas HTML)
        foreach ($textFields as $field) {
            if ($this->has($field)) {
                $value = $this->input($field);
                if (is_string($value)) {
                    // Remover etiquetas HTML, mantener solo texto
                    $sanitized = strip_tags($value);
                    // Remover caracteres de control
                    $sanitized = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $sanitized);
                    $this->merge([$field => trim($sanitized)]);
                }
            }
        }

        // Normalizar CECO (solo números y letras)
        if ($this->has('ceco')) {
            $ceco = preg_replace('/[^0-9a-zA-Z-]/', '', $this->input('ceco'));
            $this->merge(['ceco' => substr($ceco, 0, 50)]);
        }

        // Normalizar RUT si viene sin formato
        if ($this->has('rut_proveedor') && $this->rut_proveedor) {
            $this->merge([
                'rut_proveedor' => $this->formatRut($this->rut_proveedor),
            ]);
        }

        if ($this->has('rut_razon_social') && $this->rut_razon_social) {
            $this->merge([
                'rut_razon_social' => $this->formatRut($this->rut_razon_social),
            ]);
        }

        // Normalizar email
        if ($this->has('email_proveedor')) {
            $this->merge([
                'email_proveedor' => strtolower(trim($this->input('email_proveedor'))),
            ]);
        }

        // --- MAPEADO DE CAMPOS PARA VALIDACIÓN UNIFICADA ---
        
        // Mapear Cantidad (Unidad de Negocio usa cantidad_modulos, Cliente usa cantidad_participantes)
        if (!$this->has('cantidad')) {
            if ($this->has('cantidad_modulos')) {
                $this->merge(['cantidad' => $this->input('cantidad_modulos')]);
            } elseif ($this->has('cantidad_participantes')) {
                $this->merge(['cantidad' => $this->input('cantidad_participantes')]);
            }
        }

        // Mapear Descripción (Unidad de Negocio usa observacion, Cliente usa nombre_curso como parte de la desc si descripcion está vacía)
        if (empty($this->input('descripcion'))) {
            if ($this->has('observacion') && !empty($this->input('observacion'))) {
                $this->merge(['descripcion' => $this->input('observacion')]);
            } elseif ($this->has('nombre_curso') && !empty($this->input('nombre_curso'))) {
                $this->merge(['descripcion' => $this->input('nombre_curso')]);
            }
        }

        // Normalizar monto si viene con formato chileno
        if ($this->has('monto') && $this->monto) {
            $this->merge([
                'monto' => $this->normalizeAmount($this->monto),
            ]);
        }
    }

    /**
     * Format RUT to standard format (12345678-9).
     */
    private function formatRut(?string $rut): ?string
    {
        if (! $rut) {
            return null;
        }

        // Eliminar puntos, espacios y guiones
        $rut = preg_replace('/[^0-9kK]/', '', $rut);

        if (strlen($rut) < 2) {
            return $rut;
        }

        // Separar número y dígito verificador
        $dv = strtoupper(substr($rut, -1));
        $numero = substr($rut, 0, -1);

        return $numero.'-'.$dv;
    }

    /**
     * Normalize amount from Chilean format to decimal.
     */
    private function normalizeAmount($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $amount = trim((string) $value);

        // Eliminar símbolos de moneda y espacios
        $amount = preg_replace('/[^\d,.-]/', '', $amount);

        // Si tiene puntos y comas, determinar cuál es el separador decimal
        if (str_contains($amount, ',') && str_contains($amount, '.')) {
            // Si la coma está después del punto, es formato chileno (1.234.567,89)
            if (strrpos($amount, ',') > strrpos($amount, '.')) {
                $amount = str_replace('.', '', $amount);
                $amount = str_replace(',', '.', $amount);
            } else {
                // Formato inglés (1,234,567.89)
                $amount = str_replace(',', '', $amount);
            }
        } elseif (str_contains($amount, ',')) {
            // Solo coma: puede ser decimal o separador de miles
            // Asumimos decimal si hay solo una coma
            if (substr_count($amount, ',') === 1) {
                $parts = explode(',', $amount);
                // Si después de la coma hay 1 o 2 dígitos, es decimal
                if (strlen($parts[1]) <= 2) {
                    $amount = str_replace(',', '.', $amount);
                } else {
                    $amount = str_replace(',', '', $amount);
                }
            } else {
                $amount = str_replace(',', '', $amount);
            }
        }

        // Limpiar múltiples puntos decimales
        if (substr_count($amount, '.') > 1) {
            $parts = explode('.', $amount);
            $decimal = array_pop($parts);
            $integer = implode('', $parts);
            $amount = $integer.'.'.$decimal;
        }

        return is_numeric($amount) ? (float) $amount : null;
    }
}
