<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigInteger('emp_number')->primary();
            $table->string('employee_id', 255)->nullable();
            $table->string('employee_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->date('join_date')->nullable();
            $table->string('location_current_year', 255)->nullable();
            $table->string('location_future_year', 255)->nullable();
            $table->string('effective_location_year', 4)->nullable();
            $table->date('effective_location_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->date('last_payroll_date')->nullable();
            $table->string('job_level', 255)->nullable();
            $table->string('job_title', 255)->nullable();
            $table->string('npwp', 255)->nullable();
            $table->string('bpjs_kesehatan', 255)->nullable();
            $table->string('bpjs_ketenagakerjaan', 255)->nullable();
            $table->string('ktp', 255)->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('bank_account', 255)->nullable();
            $table->string('bank_name', 255)->nullable();
            $table->string('bank_account_name', 255)->nullable();
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_by', 255)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
