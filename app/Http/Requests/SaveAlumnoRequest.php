<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;
use Illuminate\Validation\Rule; // <-- Importante para validaciones avanzadas

class SaveAlumnoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['super admin', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {   
        // Obtenemos el ID del usuario del alumno. Será null al crear.
        $userId = $this->alumno?->user_id;

        $rules = [
            'name'                => ['required', new NombreValido],
            'apeP'                => ['required', new NombreValido],
            'apeM'                => ['required', new NombreValido],
            'direccion'           => ['nullable', 'string', 'max:255'],
            'email'               => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'telefono'            => ['nullable', new TelefonoValido, Rule::unique('users', 'telefono')->ignore($userId)],
            'fecha_nacimiento'    => ['nullable', 'date'],
            'sexo'                => ['required', Rule::in(['Masculino', 'Femenino', 'Otro'])],
            'telefono_emergencia' => ['nullable', new TelefonoValido],
        ];

        // Si es UPDATE, agregar regla de password opcional
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            return array_merge($rules, [
                'password' => ['nullable', 'confirmed', new PasswordSegura]
            ]);
        }

        return $rules;
    }

    /**
    * Obtiene los mensajes de error personalizados.
    */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'sexo.in' => 'El sexo seleccionado no es válido.',
        ];
    }
}
