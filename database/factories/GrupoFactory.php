<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GrupoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera una clave única, ej: "CS-101", "MT-203"
            'clave' => fake()->unique()->bothify('??-###'),
            
            // Genera un nombre de materia o grupo, ej: "Cálculo Diferencial"
            'nombre' => fake()->words(3, true),
            
            // Genera un año para la generación, ej: "2024"
            'generacion' => fake()->year(),
            
            // Genera una oración corta como observación
            'observaciones' => fake()->sentence(),
        ];
    }
}