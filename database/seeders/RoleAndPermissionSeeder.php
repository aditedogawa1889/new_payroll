<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'manage employees']);
        Permission::create(['name' => 'calculate payroll']);
        Permission::create(['name' => 'view payslip']);
        Permission::create(['name' => 'manage roles']);

        // create roles and assign created permissions

        // Admin role
        $role = Role::create(['name' => 'Admin']);
        $role->givePermissionTo(Permission::all());

        // HR role
        $role = Role::create(['name' => 'HR']);
        $role->givePermissionTo(['manage employees', 'calculate payroll', 'view payslip']);

        // Employee role
        $role = Role::create(['name' => 'Employee']);
        $role->givePermissionTo(['view payslip']);
    }
}
