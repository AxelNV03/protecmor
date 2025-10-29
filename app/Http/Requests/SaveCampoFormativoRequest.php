<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str; // <-- AÑADE ESTA LÍNEA
use Illuminate\Validation\Rule;


class SaveCampoFormativoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Solo admins pueden hacer CRUD
        return auth()->check() && auth()->user()->hasAnyRole(['super admin', 'admin']);
    }

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Reglas que aplican tanto para crear como para actualizar
        $rules = [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('campos_formativos')->ignore($this->route('campo_formativo')),
            ],
            'descripcion' => ['nullable', 'string'],
        ];
    
        // Si la petición es un POST (es decir, estamos creando),
        // entonces añadimos la regla para el campo 'tipo'.
        if ($this->isMethod('POST')) {
            $rules['tipo'] = ['required', Rule::in(['materia', 'taller'])];
        }
    
        return $rules;
    }
}
