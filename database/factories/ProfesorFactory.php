<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class ProfesorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fecha = fake()->optional()->dateTimeBetween('-10 years', '-1 year');

        return [
            // Esto se mantiene igual, crea un User para cada profesor.
            'user_id' => User::factory(),

            'apeP' => fake()->lastName(),
            'apeM' => fake()->lastName(),
            'fecha_nacimiento' => fake()->date('Y-m-d', '2000-01-01'),

            'matricula' => fake()->unique()->numerify('##########'),

            // Esto se mantiene igual.
            'especialidad' => fake()->randomElement(['Matemáticas', 'Historia', 'Ciencias', 'Literatura', 'Física']),
            
            // CAMBIO: Ahora es opcional, lo generará el 50% de las veces.
            'fecha_ingreso' => $fecha ? $fecha->format('Y-m-d') : null,

            // NUEVO: Genera un número de teléfono de emergencia.
            'telefono_emergencia' => fake()->phoneNumber(),

            // NUEVO: Asigna un sexo aleatorio de la lista.
            'sexo' => fake()->randomElement(['Masculino', 'Femenino', 'Otro']),
        ];
    }
}