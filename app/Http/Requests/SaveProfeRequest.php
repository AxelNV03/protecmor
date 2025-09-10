<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\EmailUnico;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;
use Illuminate\Validation\Rule; // <-- Importante para validaciones avanzadas


class SaveProfeRequest extends FormRequest
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
        // Obtenemos el ID del usuario del profesor. Será null al crear.
        $userId = $this->profesor?->user_id;

        $rules = [
            'name'                => ['required', new NombreValido],
            'email'               => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'telefono' => [
                'nullable', 
                new TelefonoValido, 
                Rule::unique('users', 'telefono')->ignore($userId)
            ],
            'especialidad'        => ['required', 'string', 'max:255'],
            'fecha_ingreso'       => ['nullable', 'date'],
            'sexo'                => ['required', Rule::in(['Masculino', 'Femenino', 'Otro'])],
            'telefono_emergencia' => ['nullable', new TelefonoValido],
            'password'            => ['required', 'string', 'min:6', 'confirmed'],
        ];

        // Si estamos actualizando (método PUT o PATCH), la contraseña es opcional.
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['password'] = ['nullable', 'string', 'min:6', 'confirmed'];
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


