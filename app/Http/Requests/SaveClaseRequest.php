<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveClaseRequest extends FormRequest
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
        return [
            'nombre'             => ['required', 'string', 'max:255', Rule::unique('clases')->ignore($this->clase)],
            'grupo_id'           => ['required', 'exists:grupos,id'],
            'profesor_id'        => ['required', 'exists:profesores,id'],
            'campo_formativo_id' => ['required', 'exists:campos_formativos,id'],
        ];
    }
}
