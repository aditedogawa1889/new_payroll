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
        Schema::table('employee_integrations', function (Blueprint $table) {
            $table->string('employee_name', 255)->nullable()->after('employee_id');
            $table->string('job_level', 255)->nullable()->after('job_title_effective_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_integrations', function (Blueprint $table) {
            $table->dropColumn(['employee_name', 'job_level']);
        });
    }
};
