<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employee_payroll_component', function (Blueprint $table) {
            $table->text('basis_components')->nullable()->after('value_component');
            $table->text('custom_formula')->nullable()->after('basis_components');
        });
    }

    public function down(): void
    {
        Schema::table('employee_payroll_component', function (Blueprint $table) {
            $table->dropColumn(['basis_components', 'custom_formula']);
        });
    }
};
