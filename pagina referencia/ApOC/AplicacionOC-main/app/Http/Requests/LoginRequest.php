<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class LoginRequest extends FormRequest
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
            'email' => [
                'required',
                'string',
                'email',  // Validación básica de email (más flexible)
                'max:255',
                // Verificar que el usuario existe en la base de datos
                new Exists('users', 'email'),
            ],
            'password' => [
                'required',
                'string',
                'min:6',
                'max:255',
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email debe ser una dirección válida.',
            'email.exists' => 'Este email no está registrado en el sistema.',
            'email.max' => 'El email no puede exceder 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.max' => 'La contraseña no puede exceder 255 caracteres.',
        ];
    }

    /**
     * Prepare the data for validation
     */
    protected function prepareForValidation(): void
    {
        // Convertir email a minúsculas
        $this->merge([
            'email' => strtolower($this->input('email')),
        ]);
    }
}
