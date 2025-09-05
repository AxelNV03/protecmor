<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            // Simplemente un nombre y email falsos, sin prefijos
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telefono' => fake()->phoneNumber(),
            'estatus' => 'activo',
            'remember_token' => Str::random(10),
        ];
    }
}