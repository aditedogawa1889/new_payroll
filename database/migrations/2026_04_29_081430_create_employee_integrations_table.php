<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_integrations', function (Blueprint $table) {
            $table->id('id_integration');
            $table->unsignedBigInteger('id_integ_type');
            $table->bigInteger('emp_number');
            $table->string('employee_id', 255);
            $table->string('job_title_name', 255)->nullable();
            $table->string('job_title_future_name', 255)->nullable();
            $table->date('job_title_effective_date')->nullable();
            $table->date('join_date')->nullable();
            $table->string('location_from_name', 255)->nullable();
            $table->string('location_to_name', 255)->nullable();
            $table->string('effective_year', 4)->nullable();
            $table->date('termination_date')->nullable();
            $table->date('last_payroll_date')->nullable();
            $table->integer('is_processed')->default(0);
            $table->string('created_by', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->string('updated_by', 255)->nullable();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_integ_type')->references('id_integ_type')->on('master_integration_types');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_integrations');
    }
};
