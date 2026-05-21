<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_payroll_component', function (Blueprint $table) {
            $table->id('id_emp_payroll_comp');
            
            // emp_number in employees table is usually a string (nik) or bigInteger?
            // User requested: "emp_number int (FK dari table employees)"
            // I'll use string since it's commonly varchar, but if it's int, I'll use unsignedBigInteger. 
            // I will use unsignedBigInteger or string based on usual patterns, but the user explicitly said 'int'.
            $table->bigInteger('emp_number');
            
            $table->unsignedBigInteger('id_component');
            $table->text('value_component')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();

            $table->foreign('emp_number')->references('emp_number')->on('employees')->onDelete('cascade');
            $table->foreign('id_component')->references('id_component')->on('md_component_payroll')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_payroll_component');
    }
};
