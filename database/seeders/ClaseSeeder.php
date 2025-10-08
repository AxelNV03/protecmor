<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Clase;


class ClaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crea 50 clases de ejemplo
        Clase::factory()->count(50)->create();
    }
}

