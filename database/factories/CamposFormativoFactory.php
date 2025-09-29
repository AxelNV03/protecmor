<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CamposFormativoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Genera un nombre de materia aleatorio
            'nombre' => fake()->randomElement(['Cálculo', 'Historia', 'Programación', 'Biología', 'Arte']),
            
            // Elige aleatoriamente entre 'materia' o 'taller'
            'tipo' => fake()->randomElement(['materia', 'taller']),
        ];
    }
}