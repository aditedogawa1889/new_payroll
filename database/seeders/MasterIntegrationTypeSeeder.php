<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MasterIntegrationType;

class MasterIntegrationTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['description' => 'New Employee', 'is_active' => 1],
            ['description' => 'Update Employee', 'is_active' => 1],
            ['description' => 'Termination Employee', 'is_active' => 1],
            ['description' => 'Promotion Employee', 'is_active' => 1],
            ['description' => 'Demotion Employee', 'is_active' => 1],
            ['description' => 'Mutation Employee', 'is_active' => 1],
        ];

        foreach ($types as $type) {
            MasterIntegrationType::updateOrCreate(['description' => $type['description']], $type);
        }
    }
}
