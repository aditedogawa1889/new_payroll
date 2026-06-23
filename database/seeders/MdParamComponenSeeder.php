<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MdParamComponenSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('md_param_componen')->insertOrIgnore([
            [
                'nama_param_component' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_param_component' => 'percentage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_param_component' => 'custom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
