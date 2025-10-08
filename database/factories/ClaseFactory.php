<?php

namespace Database\Factories;

use App\Models\Grupo;
use App\Models\Profesor;
use App\Models\CamposFormativo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Seeder;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Clase>
 */
class ClaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'grupo_id'           => Grupo::factory(),
            'profesor_id'        => Profesor::factory(),
            'campo_formativo_id' => CamposFormativo::factory(),
            'fecha_inicio'       => fake()->date(),
            'fecha_fin'          => NULL,
        ];
    }
}