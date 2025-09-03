<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TelefonoValido implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) return; // nullable

        if (strlen($value) > 20) {
            $fail('El teléfono no debe exceder 20 caracteres.');
            return;
        }

        $soloNumeros = preg_replace('/[^0-9]/', '', $value);
        
        if (strlen($soloNumeros) < 10) {
            $fail('El teléfono debe contener al menos 10 dígitos numericos.');
            return;
        }

        if (!preg_match('/^[\d\s+\-\(\)]+$/', $value)) {
            $fail('El teléfono contiene caracteres no válidos.');
        }
    }
}