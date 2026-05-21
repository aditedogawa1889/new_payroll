<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('md_type_komponen', function (Blueprint $table) {
            $table->id('id_type_component');
            $table->string('nama_type_component', 255);
            $table->boolean('is_active')->default(true);
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('md_type_komponen');
    }
};
