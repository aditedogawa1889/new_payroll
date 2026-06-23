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
        Schema::table('employee_loans', function (Blueprint $table) {
            $table->integer('loan_months')->default(12);
        });

        // Backfill existing loans with actual schedule counts
        $loans = DB::table('employee_loans')->select('loan_id')->get();
        foreach ($loans as $loan) {
            $scheduleCount = DB::table('employee_loans_schedule')
                ->where('loan_id', $loan->loan_id)
                ->count();
            
            if ($scheduleCount > 0) {
                DB::table('employee_loans')
                    ->where('loan_id', $loan->loan_id)
                    ->update(['loan_months' => $scheduleCount]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_loans', function (Blueprint $table) {
            $table->dropColumn('loan_months');
        });
    }
};
