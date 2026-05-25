<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('log_activity', function (Blueprint $table) {
            $table->id('id');
            $table->string('method', 100);
            $table->text('uri');
            $table->text('controller')->nullable();
            $table->string('func', 500)->nullable();
            $table->text('params')->nullable();
            $table->string('created_by', 500)->nullable();
            $table->text('ip')->nullable();
            $table->text('ip_local')->nullable();
            $table->text('latitude')->nullable();
            $table->text('longitude')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_activity');
    }
};
