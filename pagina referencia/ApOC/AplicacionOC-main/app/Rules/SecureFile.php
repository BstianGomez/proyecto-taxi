<?php

namespace App\Rules;

use App\Services\SecureFileValidator;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class SecureFile implements Rule
{
    private $errorMessage = 'El archivo no es válido.';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! $value instanceof UploadedFile) {
            $this->errorMessage = 'Debe proporcionar un archivo válido.';

            return false;
        }

        try {
            $validator = new SecureFileValidator;
            $validator->validate($value);

            return true;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            if (isset($errors['adjunto'])) {
                $this->errorMessage = is_array($errors['adjunto'])
                    ? implode(' ', $errors['adjunto'])
                    : $errors['adjunto'];
            }

            return false;
        } catch (\Exception $e) {
            $this->errorMessage = 'Error al validar el archivo. Por favor, intente con otro archivo.';

            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->errorMessage;
    }
}
