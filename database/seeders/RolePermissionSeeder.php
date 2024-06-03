<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Создание ролей
        $adminRole = Role::create(['name' => 'Admin', 'description' => 'Administrator', 'code' => 'admin', 'created_by' => 1]);
        $userRole = Role::create(['name' => 'User', 'description' => 'Regular User', 'code' => 'user', 'created_by' => 1]);
        $guestRole = Role::create(['name' => 'Guest', 'description' => 'Guest User', 'code' => 'guest', 'created_by' => 1]);

        // Создание разрешений
        $manageUsers = Permission::create(['name' => 'Manage Users', 'description' => 'Can manage users', 'code' => 'manage_users', 'created_by' => 1]);
        $viewReports = Permission::create(['name' => 'View Reports', 'description' => 'Can view reports', 'code' => 'view_reports', 'created_by' => 1]);
        $viewContent = Permission::create(['name' => 'View Content', 'description' => 'Can view content', 'code' => 'view_content', 'created_by' => 1]);

        // Присвоение разрешений ролям
        $adminRole->permissions()->attach($manageUsers);
        $adminRole->permissions()->attach($viewReports);

        $userRole->permissions()->attach($viewReports);

        $guestRole->permissions()->attach($viewContent);
    }
}
