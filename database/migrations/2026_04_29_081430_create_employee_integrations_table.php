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
            $table->integer('job_title_id');
            $table->integer('job_title_future_id')->nullable();
            $table->date('join_date');
            $table->integer('location_from_id');
            $table->integer('location_to_id');
            $table->date('effective_date');
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
