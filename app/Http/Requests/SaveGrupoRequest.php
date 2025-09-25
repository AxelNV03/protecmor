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
        return [
            'name'            => ['required', 'string', 'max:100'],
            'generacion_inicio' => ['required', 'integer', 'min:2015'],
            'generacion_fin'    => ['required', 'integer', 'gt:generacion_inicio'],
            'observaciones'     => ['nullable', 'string'],
        ];
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
