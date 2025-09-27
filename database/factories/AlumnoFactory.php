<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Alumno; // Asegúrate de importar el modelo Grupo
use App\Models\Grupo; // Asegúrate de importar el modelo Grupo
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AlumnoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Crea un nuevo usuario para cada alumno y luego usa su ID.
            'user_id' => User::factory(),
            'apeP' => fake()->lastName(),
            'apeM' => fake()->lastName(),
            
            // Usa la GrupoFactory para crear un nuevo grupo y obtener su ID.
            // 'grupo_id' => Grupo::factory(),
            'grupo_id' => NULL,
            // Genera una matrícula única de 10 dígitos.
            'matricula' => fake()->unique()->numerify('##########'),

            // Genera una fecha de nacimiento para alguien entre 18 y 25 años.
            'fecha_nacimiento' => fake()->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),

            // Elige un sexo aleatoriamente de la lista.
            'sexo' => fake()->randomElement(['Masculino', 'Femenino', 'Otro']),

            // Genera un número de teléfono de emergencia.
            'telefono_emergencia' => fake()->phoneNumber(),
        ];
    }
}