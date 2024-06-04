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

        // Новые разрешения для доступа к историям
        $getStoryPermission = Permission::create(['name' => 'Get Story Permission', 'description' => 'Can get story permission', 'code' => 'get-story-permission', 'created_by' => 1]);
        $getStoryRole = Permission::create(['name' => 'Get Story Role', 'description' => 'Can get story role', 'code' => 'get-story-role', 'created_by' => 1]);
        $getStoryUser = Permission::create(['name' => 'Get Story User', 'description' => 'Can get story user', 'code' => 'get-story-user', 'created_by' => 1]);

        // Присвоение разрешений админу
        $adminRole->permissions()->attach([$manageUsers->id, $viewReports->id, $getStoryPermission->id, $getStoryRole->id, $getStoryUser->id]);

        // Присвоение разрешений другим ролям (в примере присваивается только разрешение просмотра отчетов другим ролям)
        $userRole->permissions()->attach($viewReports);
        $guestRole->permissions()->attach($viewContent);
    }
}

