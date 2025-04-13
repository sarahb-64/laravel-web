<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos básicos
        $permissions = [
            'view_dashboard',
            'manage_users',
            'manage_roles',
            'manage_permissions',
            'view_reports',
            'create_posts',
            'edit_posts',
            'delete_posts'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $roles = [
            'admin' => [
                'view_dashboard',
                'manage_users',
                'manage_roles',
                'manage_permissions',
                'view_reports',
                'create_posts',
                'edit_posts',
                'delete_posts'
            ],
            'editor' => [
                'view_dashboard',
                'create_posts',
                'edit_posts'
            ],
            'viewer' => [
                'view_dashboard'
            ]
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName]);
            $role->givePermissionTo($permissions);
        }
    }
}