<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveGrupoRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta petición.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super admin', 'admin']);
    }

    /**
     * Obtiene las reglas de validación que aplican a la petición.
     */
    public function rules(): array
    {
        // Reglas base que aplican tanto para crear como para actualizar
        $rules = [
            'nombre'        => ['required', 'string', 'max:100'],
            'observaciones' => ['nullable', 'string'],
        ];

        // 👇 Lógica Condicional
        // Si la petición es un POST (es decir, estamos creando un grupo),

        if ($this->isMethod('POST')) {
            $rules['generacion_inicio'] = ['required', 'integer', 'min:2020'];
            $rules['generacion_fin']    = ['required', 'integer', 'gt:generacion_inicio'];
        }

        return $rules;
    }

    /**
     * Obtiene los mensajes de error personalizados.
     */
    public function messages(): array
    {
        return [
            'generacion_fin.gt' => 'El año de fin debe ser mayor que el año de inicio.',
        ];
    }
}