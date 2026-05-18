<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'nama_menu'  => 'User Management',
                'url_menu'   => 'javascript:void(0)',
                'icon_menu'  => 'fas fa-users-cog',
                'is_parent'  => 1,
                'order_menu' => 1,
                'show_menu'  => 1,
                'submenus'   => [
                    ['nama_menu' => 'User Management',   'url_menu' => 'users.index',  'icon_menu' => 'fas fa-user',        'order_menu' => 1],
                    ['nama_menu' => 'Menus',             'url_menu' => 'menus.index',  'icon_menu' => 'fas fa-list',        'order_menu' => 2],
                ],
            ],
            [
                'nama_menu'  => 'Employee',
                'url_menu'   => 'javascript:void(0)',
                'icon_menu'  => 'fas fa-id-card',
                'is_parent'  => 1,
                'order_menu' => 2,
                'show_menu'  => 1,
                'submenus'   => [
                    ['nama_menu' => 'Data Employee',       'url_menu' => 'employees.index',  'icon_menu' => 'fas fa-users',               'order_menu' => 1],
                    ['nama_menu' => 'Employee Loans',      'url_menu' => 'loans.index',      'icon_menu' => 'fas fa-hand-holding-usd',    'order_menu' => 2],
                    ['nama_menu' => 'Employee Assurance',  'url_menu' => 'assurances.index', 'icon_menu' => 'fas fa-shield-alt',          'order_menu' => 3],
                    ['nama_menu' => 'Tax Employee',        'url_menu' => 'taxes.index',      'icon_menu' => 'fas fa-file-invoice-dollar', 'order_menu' => 4],
                ],
            ],
            [
                'nama_menu'  => 'Salary',
                'url_menu'   => 'javascript:void(0)',
                'icon_menu'  => 'fas fa-money-bill-wave',
                'is_parent'  => 1,
                'order_menu' => 3,
                'show_menu'  => 1,
                'submenus'   => [
                    ['nama_menu' => 'Salary Components',  'url_menu' => 'salary-components.index',  'icon_menu' => 'fas fa-cube',          'order_menu' => 1],
                    ['nama_menu' => 'Salary Additional',  'url_menu' => 'salary-additionals.index', 'icon_menu' => 'fas fa-plus-circle',   'order_menu' => 2],
                    ['nama_menu' => 'Salary Deduction',   'url_menu' => 'salary-deductions.index',  'icon_menu' => 'fas fa-minus-circle',  'order_menu' => 3],
                    ['nama_menu' => 'Salary Setting',     'url_menu' => 'salary-settings.index',    'icon_menu' => 'fas fa-cog',           'order_menu' => 4],
                ],
            ],
            [
                'nama_menu'  => 'Payroll',
                'url_menu'   => 'javascript:void(0)',
                'icon_menu'  => 'fas fa-calculator',
                'is_parent'  => 1,
                'order_menu' => 4,
                'show_menu'  => 1,
                'submenus'   => [
                    ['nama_menu' => 'Attendance Employee', 'url_menu' => 'attendances.index',   'icon_menu' => 'fas fa-user-check',   'order_menu' => 1],
                    ['nama_menu' => 'Overtime Employee',   'url_menu' => 'overtimes.index',     'icon_menu' => 'fas fa-clock',        'order_menu' => 2],
                    ['nama_menu' => 'Payroll Calculation', 'url_menu' => 'payroll.calculate',   'icon_menu' => 'fas fa-file-invoice', 'order_menu' => 3],
                    ['nama_menu' => 'Payroll History',     'url_menu' => 'payroll.history',     'icon_menu' => 'fas fa-history',      'order_menu' => 4],
                ],
            ],
        ];

        foreach ($menus as $m) {
            $parent = Menu::create([
                'nama_menu'  => $m['nama_menu'],
                'url_menu'   => $m['url_menu'],
                'icon_menu'  => $m['icon_menu'],
                'is_parent'  => $m['is_parent'],
                'order_menu' => $m['order_menu'],
                'show_menu'  => $m['show_menu'],
            ]);

            if (isset($m['submenus'])) {
                foreach ($m['submenus'] as $sm) {
                    Menu::create([
                        'nama_menu'  => $sm['nama_menu'],
                        'url_menu'   => $sm['url_menu'],
                        'icon_menu'  => $sm['icon_menu'] ?? null,
                        'is_parent'  => 0,
                        'parent_id'  => $parent->id_menu,
                        'order_menu' => $sm['order_menu'],
                        'show_menu'  => 1,
                    ]);
                }
            }
        }
    }
}
