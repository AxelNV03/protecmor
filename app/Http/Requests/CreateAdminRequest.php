<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\EmailUnico;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;

class CreateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', new NombreValido],
            'email' => ['required', new EmailUnico], // ← Sin ID para creación
            'password' => ['required', 'confirmed', new PasswordSegura], // ← required en vez de nullable
            'telefono' => ['required', new TelefonoValido],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}