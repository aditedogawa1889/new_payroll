<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->string('component_parameter', 100)->default('general')->after('id_type_component');
        });
    }

    public function down(): void
    {
        Schema::table('md_component_payroll', function (Blueprint $table) {
            $table->dropColumn('component_parameter');
        });
    }
};
