<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $profesor = User::firstOrCreate(
            ['email' => 'profe1@protecmor.com'],
            [
                'name' => 'Juan Profesor',
                'password' => Hash::make('profe123'),
                'email_verified_at' => now(),
                'telefono' => '1231231234',
                'estatus' => 'activo'
            ]
        );
        $profesor->assignRole('profesor');

        // 4. CREAR O BUSCAR USUARIO ALUMNO
        $alumno = User::firstOrCreate(
            ['email' => 'alumno1@protecmor.com'],
            [
                'name' => 'Carlos Alumno',
                'password' => Hash::make('alumno123'),
                'email_verified_at' => now(),
                'telefono' => '3213214321',
                'estatus' => 'activo'
            ]
        );
        $alumno->assignRole('alumno');

        // Actualizar contraseñas si ya existían (opcional)
        $superAdmin->update(['password' => Hash::make('admin123')]);
        $admin->update(['password' => Hash::make('admin123')]);
        $profesor->update(['password' => Hash::make('profe123')]);
        $alumno->update(['password' => Hash::make('alumno123')]);

        $this->command->info('✅ Usuarios de prueba creados exitosamente!');
        $this->command->info('👑 Super Admin: sadmin@protecmor.com / admin123');
        $this->command->info('👤 Admin: admin1@protecmor.com / admin123');
        $this->command->info('🎓 Profesor: profe1@protecmor.com / profe123');
        $this->command->info('📚 Alumno: alumno1@protecmor.com / alumno123');
    }
}