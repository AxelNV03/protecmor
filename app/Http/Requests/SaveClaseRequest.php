<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveClaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super admin', 'admin']);
    }

    public function rules(): array
    {
        // Reglas que aplican siempre (tanto al crear como al editar)
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('clases')->ignore($this->clase),
            ],
            'profesor_id' => ['required', 'exists:profesores,id'],
        ];

        // Si la petición es POST (estamos creando), añadimos las reglas adicionales.
        if ($this->isMethod('POST')) {
            $rules['grupo_id'] = ['required', 'exists:grupos,id'];
            $rules['campo_formativo_id'] = ['required', 'exists:campos_formativos,id'];
        }

        return $rules;
    }
}