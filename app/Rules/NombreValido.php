<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NombreValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('El nombre es obligatorio.');
            return;
        }

        if (strlen($value) > 255) {
            $fail('El nombre no debe exceder 255 caracteres.');
            return;
        }

        if (!preg_match('/^[\pL\s\-\.]+$/u', $value)) {
            $fail('El nombre solo puede contener letras, espacios, guiones y puntos.');
        }
    }
}