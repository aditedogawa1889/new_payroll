<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        $admin = User::factory()->create([
            'name' => 'Admin Payroll',
            'email' => 'admin@payroll.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');

        $user = User::factory()->create([
            'name' => 'Staff HR',
            'email' => 'hr@payroll.com',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('HR');
    }

}
