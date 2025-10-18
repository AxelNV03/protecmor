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
            'campo_id'           => CamposFormativo::factory(),

            'clave'              => 'CL-' . fake()->unique()->lexify('?????'),
            'nombre'             => 'Clase de ' . fake()->word(),
            'descripcion'        => fake()->sentence(),
            'estado'             => 'en curso',
      
            'fecha_inicio'       => fake()->date(),
            'fecha_fin'          => NULL,
        ];
    }
}