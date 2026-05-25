<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MdTypeKomponenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('md_type_komponen')->insert([
            [
                'nama_type_component' => 'Penambahan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_type_component' => 'Pengurangan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
