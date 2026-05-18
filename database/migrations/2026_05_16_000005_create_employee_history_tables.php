<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Table for Termination History
        Schema::create('termination_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_number');
            $table->string('employee_id', 255);
            $table->date('termination_date');
            $table->text('reason')->nullable();
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });

        // Table for Promotion History
        Schema::create('promotion_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_number');
            $table->string('employee_id', 255);
            $table->string('previous_job_title_name', 255)->nullable();
            $table->string('new_job_title_name', 255);
            $table->date('effective_date');
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });

        // Table for Demotion History
        Schema::create('demotion_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_number');
            $table->string('employee_id', 255);
            $table->string('previous_job_title_name', 255)->nullable();
            $table->string('new_job_title_name', 255);
            $table->date('effective_date');
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });

        // Table for Mutation History
        Schema::create('mutation_employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_number');
            $table->string('employee_id', 255);
            $table->string('previous_location_name', 255)->nullable();
            $table->string('new_location_name', 255);
            $table->date('effective_date');
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termination_employees');
        Schema::dropIfExists('promotion_employees');
        Schema::dropIfExists('demotion_employees');
        Schema::dropIfExists('mutation_employees');
    }
};
