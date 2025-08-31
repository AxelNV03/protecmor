<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminsTestSeeder extends Seeder
{
    public function run()
    {
        // Crear rol admin si no existe
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // 👑 Crear 6 administradores
        User::factory()->count(6)->create()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
            $this->command->info("✅ Admin creado: {$user->name} - {$user->email}");
        });

        $this->command->info('🎉 ¡6 usuarios administradores creados exitosamente!');
        $this->command->info('📧 Emails: admin.*@example.com');
        $this->command->info('🔑 Password: password');
    }
}