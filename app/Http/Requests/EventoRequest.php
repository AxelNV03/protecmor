<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EventoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $fechaRules = 'required|date|after_or_equal:today';
        if ($this->isMethod('PUT') && $this->route('evento') && $this->route('evento')->fecha->toDateString() == $this->get('fecha')) {
            $fechaRules = 'required|date'; // Permite mantener la fecha actual si ya es pasada
        }

        return [
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'fecha' => $fechaRules,
            'hora' => 'nullable|date_format:H:i',
            'duracion' => 'nullable|string|max:50',
            'costo' => 'nullable|numeric|min:0',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'publico' => ['required', Rule::in(['alumnos', 'general'])],
            'incluido_mensualidad' => 'boolean',
            ];
    }
        
    public function messages()
    {
        return [
            'fecha.after_or_equal' => 'La fecha del evento no puede ser una fecha pasada.'
        ];
    }
}