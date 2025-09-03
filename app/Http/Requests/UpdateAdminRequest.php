<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NombreValido;
use App\Rules\EmailUnico;
use App\Rules\PasswordSegura;
use App\Rules\TelefonoValido;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('id');

        return [
            'name' => ['required', new NombreValido],
            'email' => ['required', new EmailUnico($adminId)],
            'password' => ['nullable', 'confirmed', new PasswordSegura],
            'telefono' => ['nullable', new TelefonoValido],
        ];
    }

    public function messages(): array
    {
        return [
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}