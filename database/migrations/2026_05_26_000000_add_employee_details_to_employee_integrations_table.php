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
            $table->string('email', 255)->nullable()->after('employee_name');
            $table->string('npwp', 255)->nullable()->after('last_payroll_date');
            $table->string('bpjs_kesehatan', 255)->nullable()->after('npwp');
            $table->string('bpjs_ketenagakerjaan', 255)->nullable()->after('bpjs_kesehatan');
            $table->string('ktp', 255)->nullable()->after('bpjs_ketenagakerjaan');
            $table->string('gender', 50)->nullable()->after('ktp');
            $table->string('bank_account', 255)->nullable()->after('gender');
            $table->string('bank_name', 255)->nullable()->after('bank_account');
            $table->string('bank_account_name', 255)->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_integrations', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'npwp',
                'bpjs_kesehatan',
                'bpjs_ketenagakerjaan',
                'ktp',
                'gender',
                'bank_account',
                'bank_name',
                'bank_account_name'
            ]);
        });
    }
};
