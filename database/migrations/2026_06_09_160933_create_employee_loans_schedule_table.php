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
        Schema::create('employee_loans_schedule', function (Blueprint $table) {
            $table->increments('loan_schedule_id');
            $table->unsignedInteger('loan_id');
            $table->integer('month_number');
            $table->integer('year_number');
            $table->decimal('amount', 18, 0);
            $table->decimal('remaining_amount', 18, 0);
            $table->decimal('paid_amount', 18, 0)->default(0);
            $table->dateTime('payment_date')->nullable();
            $table->integer('payment_status')->default(1); // 1 = Aktif, 2 = Lunas
            $table->dateTime('created_at')->nullable();
            $table->string('created_by', 200)->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->string('updated_by', 200)->nullable();

            $table->foreign('loan_id')->references('loan_id')->on('employee_loans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_loans_schedule');
    }
};
