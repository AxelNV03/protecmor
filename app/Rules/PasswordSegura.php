<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PasswordSegura implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) return; // nullable

        if (strlen($value) < 8) {
            $fail('La contraseña debe tener al menos 8 caracteres.');
            return;
        }

        if (!preg_match('/[a-z]/', $value)) {
            $fail('La contraseña debe contener al menos una minúscula.');
            return;
        }

        if (!preg_match('/[A-Z]/', $value)) {
            $fail('La contraseña debe contener al menos una mayúscula.');
            return;
        }

        if (!preg_match('/\d/', $value)) {
            $fail('La contraseña debe contener al menos un número.');
            return;
        }

        if (!preg_match('/[@$!%*?&_\-]/', $value)) {
            $fail('La contraseña debe contener al menos un carácter especial (@$!%*?&_-).');
        }
    }
}