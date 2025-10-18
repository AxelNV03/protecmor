<?php

namespace Database\Seeders;

use App\Models\Alumno;
use Illuminate\Database\Seeder;

class AlumnoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ La llamada debe ser a Alumno::factory()
        Alumno::factory()
            ->count(50)
            ->create()
            ->each(function ($alumno) {
                $alumno->user->assignRole('alumno');
            });
    }
}