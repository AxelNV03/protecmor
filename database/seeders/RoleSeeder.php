<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // 1. CREAR LOS ROLES
        $superAdmin = Role::firstOrCreate(['name' => 'super admin']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $profesor = Role::firstOrCreate(['name' => 'profesor']);
        $alumno = Role::firstOrCreate(['name' => 'alumno']);

        // 2. CREAR PERMISOS BÁSICOS
        $permissions = [
            // Dashboard
            'ver-dashboard',
            
            // Gestión de usuarios
            'ver-usuarios',
            'crear-usuarios', 
            'editar-usuarios',
            'eliminar-usuarios',
            
            // Gestión de roles
            'ver-roles',
            'crear-roles',
            'editar-roles',
            'eliminar-roles',
            
            // Contenido académico
            'ver-calificaciones',
            'crear-calificaciones',
            'editar-calificaciones',
            'eliminar-calificaciones',
            
            // Perfil
            'editar-perfil',
            'ver-perfil'
        ];

        // Crear cada permiso en la base de datos
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }


        // Super Admin: TODOS los permisos
        $superAdmin->givePermissionTo(Permission::all());

        // Admin: Casi todos los permisos excepto roles
        $admin->givePermissionTo([
            'ver-dashboard',
            'ver-usuarios',
            'crear-usuarios',
            'editar-usuarios', 
            'eliminar-usuarios',
            'ver-calificaciones',
            'crear-calificaciones',
            'editar-calificaciones',
            'eliminar-calificaciones',
            'editar-perfil',
            'ver-perfil'
        ]);

        // Profesor: Gestionar calificaciones y ver contenido
        $profesor->givePermissionTo([
            'ver-dashboard',
            'ver-calificaciones',
            'crear-calificaciones',
            'editar-calificaciones',
            'editar-perfil',
            'ver-perfil'
        ]);

        // Alumno: Solo ver su perfil y calificaciones
        $alumno->givePermissionTo([
            'ver-dashboard',
            'ver-calificaciones',
            'editar-perfil',
            'ver-perfil'
        ]);

        $this->command->info('Roles y permisos creados exitosamente!');
    }
}