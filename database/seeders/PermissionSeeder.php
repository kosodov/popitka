<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $entities = ['user', 'role', 'permission'];
        $actions = ['get-list', 'read', 'create', 'update', 'delete', 'restore'];


        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::create([
                    'name' => "{$action}-{$entity}",
                    'description' => ucfirst($action) . " " . ucfirst($entity),
                    'code' => "{$action}_{$entity}",
                    'created_by' => 1,
                ]);
            }
        }
    }
}
