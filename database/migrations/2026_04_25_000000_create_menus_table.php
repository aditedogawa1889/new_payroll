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
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu');
            $table->text('uri')->nullable();
            $table->string('icon')->nullable();
            $table->integer('is_parent')->default(0);
            $table->integer('parent_id')->nullable();
            $table->integer('order_menu')->default(0);
            $table->integer('show_menu')->default(1);
            $table->integer('is_active')->default(1);
            $table->string('permission_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
