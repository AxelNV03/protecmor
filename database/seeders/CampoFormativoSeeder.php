<?php

namespace Database\Seeders;

use App\Models\CamposFormativo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampoFormativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea 20 campos formativos usando la factory
        CamposFormativo::factory()->count(20)->create();
    }
}