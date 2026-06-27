<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'manage courses', 'manage schedules', 'manage enrollments',
            'manage users', 'manage news', 'manage gallery',
            'manage inquiries', 'manage certificates', 'view reports',
            'manage settings',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $registrar = Role::firstOrCreate(['name' => 'Registrar']);
        $registrar->givePermissionTo(['manage enrollments', 'manage certificates', 'view reports']);

        $coordinator = Role::firstOrCreate(['name' => 'Training Coordinator']);
        $coordinator->givePermissionTo(['manage courses', 'manage schedules', 'manage enrollments', 'view reports']);

        $instructor = Role::firstOrCreate(['name' => 'Instructor']);
        $instructor->givePermissionTo(['manage courses', 'manage schedules']);
    }
}
