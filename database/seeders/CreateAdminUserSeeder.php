<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        // Crear el rol de admin
        $adminRole = Role::create(['name' => 'admin']);

        // Crear permisos
        $permissions = [
            'view-projects',
            'create-projects',
            'edit-projects',
            'delete-projects',
            'manage-projects'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar todos los permisos al rol admin
        $adminRole->givePermissionTo(Permission::all());

        // Crear un usuario admin si no existe
        $admin = User::where('email', 'admin@example.com')->first();
        if (!$admin) {
            $admin = User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        // Asignar el rol admin al usuario
        $admin->assignRole($adminRole);
    }
}