<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreatePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $permissions = [

            // Auth
            ['name' => 'auth.login', 'label' => 'Login', 'module' => 'auth'],
            ['name' => 'auth.logout', 'label' => 'Logout', 'module' => 'auth'],
            ['name' => 'auth.refresh', 'label' => 'Refresh Token', 'module' => 'auth'],
            ['name' => 'auth.impersonate', 'label' => 'Impersonate User', 'module' => 'auth'],

            // User
            ['name' => 'user.view', 'label' => 'View Users', 'module' => 'user'],
            ['name' => 'user.create', 'label' => 'Create User', 'module' => 'user'],
            ['name' => 'user.update', 'label' => 'Update User', 'module' => 'user'],
            ['name' => 'user.deactivate', 'label' => 'Deactivate User', 'module' => 'user'],

            // Role
            ['name' => 'role.view', 'label' => 'View Roles', 'module' => 'role'],
            ['name' => 'role.create', 'label' => 'Create Role', 'module' => 'role'],
            ['name' => 'role.update', 'label' => 'Update Role', 'module' => 'role'],
            ['name' => 'role.delete', 'label' => 'Delete Role', 'module' => 'role'],
            ['name' => 'role.assign', 'label' => 'Assign Role to User', 'module' => 'role'],
            ['name' => 'role.revoke', 'label' => 'Revoke Role from User', 'module' => 'role'],

            // Org Unit
            ['name' => 'organization.view', 'label' => 'View Org Units', 'module' => 'organization'],
            ['name' => 'organization.create', 'label' => 'Create Org Unit', 'module' => 'organization'],
            ['name' => 'organization.update', 'label' => 'Update Org Unit', 'module' => 'organization'],
            ['name' => 'organization.delete', 'label' => 'Delete Org Unit', 'module' => 'organization'],

            // Permission / Policy
            ['name' => 'permission.view', 'label' => 'View Permissions', 'module' => 'permission'],
            ['name' => 'permission.assign', 'label' => 'Assign Permission', 'module' => 'permission'],

            ['name' => 'policy.view', 'label' => 'View Access Policies', 'module' => 'policy'],
            ['name' => 'policy.create', 'label' => 'Create Access Policy', 'module' => 'policy'],
            ['name' => 'policy.update', 'label' => 'Update Access Policy', 'module' => 'policy'],
            ['name' => 'policy.delete', 'label' => 'Delete Access Policy', 'module' => 'policy'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $permission['name']],
                array_merge($permission, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }
}
