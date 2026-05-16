<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users_menus', function (Blueprint $table) {
            $table->id('id_users_menus');
            $table->unsignedBigInteger('id_user');
            $table->text('id_menus')->nullable()->comment('JSON array of child menu IDs, e.g. [1,2,3]');
            $table->string('created_by', 255)->nullable();
            $table->string('updated_by', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users_menus');
    }
};
