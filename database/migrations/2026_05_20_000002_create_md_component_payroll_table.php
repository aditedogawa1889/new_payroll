<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('md_component_payroll', function (Blueprint $table) {
            $table->id('id_component');
            $table->string('nama_component', 255);
            $table->unsignedBigInteger('id_type_component');
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_type_component')->references('id_type_component')->on('md_type_komponen')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_component_payroll');
    }
};
