<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class EmailUnico implements ValidationRule
{
    protected $ignoreId;

    public function __construct($ignoreId = null)
    {
        $this->ignoreId = $ignoreId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            $fail('El email es obligatorio.');
            return;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $fail('El formato del email no es válido.');
            return;
        }

        // Consulta directa más simple
        $query = \App\Models\User::where('email', $value);
        
        if ($this->ignoreId) {
            $query->where('id', '!=', $this->ignoreId);
        }
    
        if ($query->exists()) {
            $fail('Este email ya está registrado.');
        }
    }
}