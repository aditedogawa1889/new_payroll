<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Role Admin Payroll
        $adminRole = Role::firstOrCreate(['name' => 'Admin Payroll', 'guard_name' => 'web']);

        // 2. Define Menus
        $menus = [
            [
                'nama_menu' => 'User Management',
                'uri' => '#',
                'icon' => 'fas fa-users-cog',
                'is_parent' => 1,
                'order_menu' => 1,
                'submenus' => [
                    ['nama_menu' => 'User Management', 'uri' => 'users.index', 'icon' => 'fas fa-user', 'permission' => 'user.index'],
                    ['nama_menu' => 'Role and Permission', 'uri' => 'roles.index', 'icon' => 'fas fa-user-shield', 'permission' => 'role.index'],
                    ['nama_menu' => 'Menus', 'uri' => 'menus.index', 'icon' => 'fas fa-list', 'permission' => 'menu.index'],
                ]
            ],
            [
                'nama_menu' => 'Employee',
                'uri' => '#',
                'icon' => 'fas fa-id-card',
                'is_parent' => 1,
                'order_menu' => 2,
                'submenus' => [
                    ['nama_menu' => 'Data Employee', 'uri' => 'employees.index', 'icon' => 'fas fa-users', 'permission' => 'employee.index'],
                    ['nama_menu' => 'Employee Loans', 'uri' => 'loans.index', 'icon' => 'fas fa-hand-holding-usd', 'permission' => 'loan.index'],
                    ['nama_menu' => 'Employee Assurance', 'uri' => 'assurances.index', 'icon' => 'fas fa-shield-alt', 'permission' => 'assurance.index'],
                    ['nama_menu' => 'Tax Employee', 'uri' => 'taxes.index', 'icon' => 'fas fa-file-invoice-dollar', 'permission' => 'tax.index'],
                ]
            ],
            [
                'nama_menu' => 'Salary',
                'uri' => '#',
                'icon' => 'fas fa-money-bill-wave',
                'is_parent' => 1,
                'order_menu' => 3,
                'submenus' => [
                    ['nama_menu' => 'Salary Components', 'uri' => 'salary-components.index', 'icon' => 'fas fa-cube', 'permission' => 'salary-component.index'],
                    ['nama_menu' => 'Salary Additional', 'uri' => 'salary-additionals.index', 'icon' => 'fas fa-plus-circle', 'permission' => 'salary-additional.index'],
                    ['nama_menu' => 'Salary Deduction', 'uri' => 'salary-deductions.index', 'icon' => 'fas fa-minus-circle', 'permission' => 'salary-deduction.index'],
                    ['nama_menu' => 'Salary Setting', 'uri' => 'salary-settings.index', 'icon' => 'fas fa-cog', 'permission' => 'salary-setting.index'],
                ]
            ],
            [
                'nama_menu' => 'Payroll',
                'uri' => '#',
                'icon' => 'fas fa-calculator',
                'is_parent' => 1,
                'order_menu' => 4,
                'submenus' => [
                    ['nama_menu' => 'Attendance Employee', 'uri' => 'attendances.index', 'icon' => 'fas fa-user-check', 'permission' => 'attendance.index'],
                    ['nama_menu' => 'Overtime Employee', 'uri' => 'overtimes.index', 'icon' => 'fas fa-clock', 'permission' => 'overtime.index'],
                    ['nama_menu' => 'Payroll Calculation', 'uri' => 'payroll.calculate', 'icon' => 'fas fa-file-invoice', 'permission' => 'payroll.calculate'],
                    ['nama_menu' => 'Payroll History', 'uri' => 'payroll.history', 'icon' => 'fas fa-history', 'permission' => 'payroll.history'],
                ]
            ],
        ];

        foreach ($menus as $m) {
            $parent = Menu::create([
                'nama_menu' => $m['nama_menu'],
                'uri' => $m['uri'],
                'icon' => $m['icon'],
                'is_parent' => $m['is_parent'],
                'order_menu' => $m['order_menu'],
                'show_menu' => 1,
                'is_active' => 1,
            ]);

            if (isset($m['submenus'])) {
                foreach ($m['submenus'] as $sm) {
                    // Create permission if it doesn't exist
                    $permissionId = null;
                    if (isset($sm['permission'])) {
                        $p = Permission::firstOrCreate(['name' => $sm['permission'], 'guard_name' => 'web']);
                        $adminRole->givePermissionTo($sm['permission']);
                        $permissionId = $p->id;
                    }

                    Menu::create([
                        'nama_menu' => $sm['nama_menu'],
                        'uri' => $sm['uri'],
                        'icon' => $sm['icon'] ?? null,
                        'is_parent' => 0,
                        'parent_id' => $parent->id_menu,
                        'order_menu' => 0,
                        'show_menu' => 1,
                        'is_active' => 1,
                        'permission_id' => $permissionId,
                    ]);
                }
            }
        }
    }
}
