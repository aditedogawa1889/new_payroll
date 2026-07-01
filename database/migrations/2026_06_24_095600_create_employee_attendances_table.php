<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id('id_attendance');
            $table->bigInteger('employee_id');
            $table->date('period_st');
            $table->date('period_en');
            $table->date('payroll_date')->nullable();
            $table->decimal('attendance_days', 18, 2)->default(0);
            $table->timestamps();

            $table->foreign('employee_id')->references('emp_number')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_attendances');
    }
};
