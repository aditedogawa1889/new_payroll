<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('menus');
        Schema::enableForeignKeyConstraints();

        Schema::create('menus', function (Blueprint $table) {
            $table->id('id_menu');
            $table->string('nama_menu', 255);
            $table->string('url_menu', 255)->nullable();
            $table->string('icon_menu', 255)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('order_menu')->default(0);
            $table->integer('is_parent')->default(0);
            $table->integer('show_menu')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
