<?php

namespace Database\Factories;

use App\Models\Grupo; // 👈 1. Importa el modelo
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

        // 1. Genera un año de inicio aleatorio (ej. 2020)
        $anioInicio = fake()->year();

        // 2. Calcula el año de fin sumándole 3
        $anioFin = (int)$anioInicio + 3;

        return [
            // Genera una clave única, ej: "CS-101", "MT-203"
            'clave' => fake()->unique()->bothify('??-###'),
            
            // Genera un nombre de materia o grupo, ej: "Cálculo Diferencial"
            'nombre' => fake()->words(3, true),
            
            // Genera un año para la generación, ej: "2024"
            'generacion' => $anioInicio . ' - ' . $anioFin,
            
            // Genera una oración corta como observación
            'observaciones' => fake()->sentence(),
        ];
    }
}