<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Disable Termwind styling
        //if (class_exists('Termwind\ValueObjects\Styles')) {
            //\Termwind\ValueObjects\Styles::disable();
        //}

        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

        // Create permissions
        Permission::create(['name' => 'dashboard.view']);
        Permission::create(['name' => 'users.manage']);
        Permission::create(['name' => 'roles.manage']);
        Permission::create(['name' => 'settings.manage']);

        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // Assign basic permissions to user role
        $userRole->givePermissionTo(['dashboard.view']);
    }
}