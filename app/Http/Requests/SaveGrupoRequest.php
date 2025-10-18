<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str; // <-- AÑADE ESTA LÍNEA


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
     * Usa el nombre en mayusculas
     */
    protected function prepareForValidation(): void
    {
        // Si el campo 'nombre' existe en la petición,
        // lo convertimos a mayúsculas antes de validar.
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => Str::upper($this->input('nombre')),
            ]);
        }
    }


    /**
     * Obtiene las reglas de validación que aplican a la petición.
     */
    public function rules(): array
    {
        $rules = [
            // 👇 CORRECCIÓN AQUÍ
            'nombre' => [
                'required', 
                'string', 
                'max:100', 
                Rule::unique('grupos')->ignore($this->route('grupo'))
            ],
            'observaciones' => ['nullable', 'string'],
        ];

        if ($this->isMethod('POST')) {
            $rules['generacion_inicio'] = ['required', 'integer', 'min:2015'];
            $rules['generacion_fin']    = ['required', 'integer', 'gte:generacion_inicio'];
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