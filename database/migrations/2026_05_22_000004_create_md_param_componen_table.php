<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create md_param_componen table
        Schema::create('md_param_componen', function (Blueprint $table) {
            $table->id('id_param_component');
            $table->string('nama_param_component', 255);
            $table->timestamps();
        });

        // 2. Seed default values
        DB::table('md_param_componen')->insert([
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

        // 3. Add column to md_component_payroll
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->unsignedBigInteger('id_param_component')->default(1)->after('id_type_component');
        });

        // 4. Migrate existing data
        $components = DB::table('md_component_payroll')->get();
        foreach ($components as $component) {
            $paramId = 1; // default to general
            if (isset($component->component_parameter)) {
                if ($component->component_parameter === 'percentage') {
                    $paramId = 2;
                } elseif ($component->component_parameter === 'custom') {
                    $paramId = 3;
                }
            }
            DB::table('md_component_payroll')
                ->where('id_component', $component->id_component)
                ->update(['id_param_component' => $paramId]);
        }

        // 5. Add foreign key
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->foreign('id_param_component')->references('id_param_component')->on('md_param_componen')->onDelete('cascade');
        });

        // 6. Drop old column
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->dropColumn('component_parameter');
        });
    }

    public function down(): void
    {
        // 1. Recreate column component_parameter
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->string('component_parameter', 100)->default('general')->after('id_type_component');
        });

        // 2. Restore data
        $components = DB::table('md_component_payroll')->get();
        foreach ($components as $component) {
            $paramName = 'general';
            if ($component->id_param_component == 2) {
                $paramName = 'percentage';
            } elseif ($component->id_param_component == 3) {
                $paramName = 'custom';
            }
            DB::table('md_component_payroll')
                ->where('id_component', $component->id_component)
                ->update(['component_parameter' => $paramName]);
        }

        // 3. Drop foreign key and column id_param_component
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->dropForeign(['id_param_component']);
            $table->dropColumn('id_param_component');
        });

        // 4. Drop md_param_componen table
        Schema::dropIfExists('md_param_componen');
    }
};
