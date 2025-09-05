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
        // Usamos la factory para crear 50 alumnos.
        // La factory se encargará de crear un User y un Grupo para cada uno.
        $alumnos = Alumno::factory()->count(5)->create();

        // Ahora, recorremos los alumnos recién creados para asignar
        // el rol 'alumno' a su usuario correspondiente.
        foreach ($alumnos as $alumno) {
            // Accedemos al usuario a través de la relación que definimos en el modelo
            $alumno->user->assignRole('alumno');
        }
    }
}