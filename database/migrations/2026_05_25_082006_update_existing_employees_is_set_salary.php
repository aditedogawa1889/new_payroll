<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $empNumbers = DB::table('employee_payroll_component')
            ->where('is_active', true)
            ->distinct()
            ->pluck('emp_number')
            ->toArray();

        if (!empty($empNumbers)) {
            DB::table('employees')
                ->whereIn('emp_number', $empNumbers)
                ->update(['is_set_salary' => 1]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('employees')->update(['is_set_salary' => 0]);
    }
};
