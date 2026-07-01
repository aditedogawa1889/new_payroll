<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_overtime', function (Blueprint $table) {
            $table->id('id_overtime');
            $table->bigInteger('employee_id');
            $table->date('period_ot_st');
            $table->date('period_ot_en');
            $table->date('payroll_ot_date')->nullable();
            $table->decimal('overtime_hours', 18, 2)->default(0);
            $table->decimal('overtime_meals', 18, 0)->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('emp_number')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_overtime');
    }
};
