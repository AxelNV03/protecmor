<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Profesor; // <-- Añade esta línea
use App\Models\Alumno; // <-- Añade esta línea


class UserSeeder extends Seeder
{
    public function run()
    {
        // 1. CREAR O BUSCAR USUARIO SUPER ADMIN
        $superAdmin = User::firstOrCreate(
            ['email' => 'sadmin@protecmor.com'],
            [
                'name' => 'Super Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'telefono' => '1234567890',
                'estatus' => 'activo'
            ]
        );
        $superAdmin->assignRole('super admin');

        // 2. CREAR O BUSCAR USUARIO ADMIN
        $admin = User::firstOrCreate(
            ['email' => 'admin1@protecmor.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'telefono' => '0987654321',
                'estatus' => 'activo'
            ]
        );
        $admin->assignRole('admin');

        // 3. CREAR O BUSCAR USUARIO PROFESOR
        $profesorUser = User::firstOrCreate(
            ['email' => 'profe1@protecmor.com'],
            [
                'name' => 'Juan',
                'password' => Hash::make('profe123'),
                'email_verified_at' => now(),
                'telefono' => '1231231234',
                'estatus' => 'activo'
            ]
        );
        $profesorUser->assignRole('profesor');

        // Si el perfil de profesor no existe, lo creamos con datos mínimos
        $profesorUser->profesor()->firstOrCreate([],
            [
                'apeP' => 'Profe',
                'apeM' => 'Profe',
                'sexo' => 'Masculino',
                'matricula' => Profesor::generarMatricula('Juan', 'Profe', 'Profe'),
                'especialidad' => 'Matemáticas',
                'fecha_nacimiento' => '1985-05-10',
                'direccion' => 'Calle Falsa 123',
            ]
        );

        // --- CREAR O BUSCAR USUARIO ALUMNO ---
        $alumnoUser = User::firstOrCreate(
            ['email' => 'alumno1@protecmor.com'],
            [
                'name' => 'Carlos Alumno',
                'password' => Hash::make('alumno123'),
                'email_verified_at' => now(),
                'telefono' => '3213214321',
                'estatus' => 'activo'
            ]
        );
        $alumnoUser->assignRole('alumno');

        // Si el perfil de alumno no existe, lo creamos con datos mínimos
        $alumnoUser->alumno()->firstOrCreate([],
            [
                'apeP' => 'Sánchez',
                'apeM' => 'López',
                'sexo' => 'Masculino',
                'fecha_nacimiento' => '2005-08-20',
                'matricula' => Alumno::generarMatricula('Carlos Alumno', 'Sánchez', 'López'),
            ]
        );

        $this->command->info('✅ Usuarios de prueba creados exitosamente!');
        $this->command->info('👑 Super Admin: sadmin@protecmor.com / sadmin123');
        $this->command->info('👤 Admin: admin1@protecmor.com / admin123');
        $this->command->info('🎓 Profesor: profe1@protecmor.com / profe123');
        $this->command->info('📚 Alumno: alumno1@protecmor.com / alumno123');
    }
}