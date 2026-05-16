<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_integration_types', function (Blueprint $table) {
            $table->id('id_integ_type');
            $table->string('description', 255);
            $table->integer('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_integration_types');
    }
};
