<?php

namespace Database\Seeders;

use App\Models\Profesor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfesorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Esta orden no cambia. Sigue pidiendo 15 profesores.
        $profes = Profesor::factory()->count(5)->create();

        foreach ($profes as $profesor) {
            $profesor->user->assignRole('profesor');
        }
    }
}