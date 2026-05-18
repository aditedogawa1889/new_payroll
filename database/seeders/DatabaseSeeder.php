<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use App\Models\UsersMenu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            MenuSeeder::class,
            MasterIntegrationTypeSeeder::class,
        ]);

        // Ambil semua child menu IDs
        $allChildMenuIds = Menu::where('is_parent', 0)->pluck('id_menu')->toArray();
        $payrollMenuIds  = Menu::where('is_parent', 0)
            ->whereHas('parent', fn($q) => $q->whereIn('nama_menu', ['Employee', 'Salary', 'Payroll']))
            ->pluck('id_menu')->toArray();
        $managerMenuIds  = Menu::where('is_parent', 0)
            ->whereHas('parent', fn($q) => $q->where('nama_menu', 'Payroll'))
            ->pluck('id_menu')->toArray();

        // Admin IT Pusat (permission id: 99) - akses semua menu
        $admin = User::create([
            'name'          => 'Admin IT Pusat',
            'email'         => 'admin@payroll.com',
            'password'      => Hash::make('password'),
            'id_permission' => [99],
        ]);

        UsersMenu::create([
            'id_user'    => $admin->id,
            'id_menus'   => $allChildMenuIds,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        // Admin Payroll (permission id: 1) - akses employee, salary, payroll
        $payrollAdmin = User::create([
            'name'          => 'Admin Payroll',
            'email'         => 'payroll@payroll.com',
            'password'      => Hash::make('password'),
            'id_permission' => [1],
        ]);

        UsersMenu::create([
            'id_user'    => $payrollAdmin->id,
            'id_menus'   => $payrollMenuIds,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);

        // Manager Payroll (permission id: 11) - akses hanya payroll
        $manager = User::create([
            'name'          => 'Manager Payroll',
            'email'         => 'manager@payroll.com',
            'password'      => Hash::make('password'),
            'id_permission' => [11],
        ]);

        UsersMenu::create([
            'id_user'    => $manager->id,
            'id_menus'   => $managerMenuIds,
            'created_by' => 'system',
            'updated_by' => 'system',
        ]);
    }
}
