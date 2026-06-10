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
        Schema::create('employee_loans', function (Blueprint $table) {
            $table->increments('loan_id');
            // employee_id references emp_number which is bigInteger
            $table->bigInteger('employee_id');
            $table->decimal('loan_amount', 18, 0);
            $table->date('loan_date');
            $table->text('loan_description')->nullable();
            $table->integer('loan_status')->default(1); // 1 = Aktif, 2 = Lunas
            $table->dateTime('loan_created_at')->nullable();
            $table->string('loan_created_by', 200)->nullable();
            $table->dateTime('loan_updated_at')->nullable();
            $table->string('loan_updated_by', 200)->nullable();

            $table->foreign('employee_id')->references('emp_number')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_loans');
    }
};
