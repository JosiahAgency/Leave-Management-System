<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = ['users', 'leavetypes', 'roles', 'departments', 'permissions'];
        $actions = ['view', 'create', 'update', 'delete', 'restore', 'force delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action $resource"]);
            }
        }

        $roles = ['admin', 'manager', 'humanResources', 'user'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }


    }
}
