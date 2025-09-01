<?php

namespace Database\Factories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition() // ✅ ¡FALTA ESTA LÍNEA!
    {
        return [
            'name' => 'Admin ' . $this->faker->firstName(),
            'email' => 'admin.' . $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'telefono' => $this->faker->phoneNumber(),
            'estatus' => 'activo',
            'remember_token' => Str::random(10),
        ];
    }
}