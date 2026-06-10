<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'users',
            'employees',
            'employee_integrations',
            'permissions',
            'menus',
            'users_menus',
            'promotion_employees',
            'demotion_employees',
            'mutation_employees',
            'personal_access_tokens',
            'termination_employees',
            'md_type_komponen',
            'md_component_payroll',
            'employee_payroll_component',
            'log_activity',
            'md_param_componen'
        ];

        $driver = DB::getDriverName();

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (Schema::hasColumn($table, 'created_at')) {
                    if ($driver === 'sqlsrv') {
                        DB::statement("UPDATE [$table] SET [created_at] = DATEADD(hour, 7, [created_at]) WHERE [created_at] IS NOT NULL");
                    } elseif ($driver === 'sqlite') {
                        DB::statement("UPDATE `$table` SET `created_at` = datetime(`created_at`, '+7 hours') WHERE `created_at` IS NOT NULL");
                    } else {
                        DB::statement("UPDATE `$table` SET `created_at` = DATE_ADD(`created_at`, INTERVAL 7 HOUR) WHERE `created_at` IS NOT NULL");
                    }
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    if ($driver === 'sqlsrv') {
                        DB::statement("UPDATE [$table] SET [updated_at] = DATEADD(hour, 7, [updated_at]) WHERE [updated_at] IS NOT NULL");
                    } elseif ($driver === 'sqlite') {
                        DB::statement("UPDATE `$table` SET `updated_at` = datetime(`updated_at`, '+7 hours') WHERE `updated_at` IS NOT NULL");
                    } else {
                        DB::statement("UPDATE `$table` SET `updated_at` = DATE_ADD(`updated_at`, INTERVAL 7 HOUR) WHERE `updated_at` IS NOT NULL");
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users',
            'employees',
            'employee_integrations',
            'permissions',
            'menus',
            'users_menus',
            'promotion_employees',
            'demotion_employees',
            'mutation_employees',
            'personal_access_tokens',
            'termination_employees',
            'md_type_komponen',
            'md_component_payroll',
            'employee_payroll_component',
            'log_activity',
            'md_param_componen'
        ];

        $driver = DB::getDriverName();

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (Schema::hasColumn($table, 'created_at')) {
                    if ($driver === 'sqlsrv') {
                        DB::statement("UPDATE [$table] SET [created_at] = DATEADD(hour, -7, [created_at]) WHERE [created_at] IS NOT NULL");
                    } elseif ($driver === 'sqlite') {
                        DB::statement("UPDATE `$table` SET `created_at` = datetime(`created_at`, '-7 hours') WHERE `created_at` IS NOT NULL");
                    } else {
                        DB::statement("UPDATE `$table` SET `created_at` = DATE_SUB(`created_at`, INTERVAL 7 HOUR) WHERE `created_at` IS NOT NULL");
                    }
                }
                if (Schema::hasColumn($table, 'updated_at')) {
                    if ($driver === 'sqlsrv') {
                        DB::statement("UPDATE [$table] SET [updated_at] = DATEADD(hour, -7, [updated_at]) WHERE [updated_at] IS NOT NULL");
                    } elseif ($driver === 'sqlite') {
                        DB::statement("UPDATE `$table` SET `updated_at` = datetime(`updated_at`, '-7 hours') WHERE `updated_at` IS NOT NULL");
                    } else {
                        DB::statement("UPDATE `$table` SET `updated_at` = DATE_SUB(`updated_at`, INTERVAL 7 HOUR) WHERE `updated_at` IS NOT NULL");
                    }
                }
            }
        }
    }
};
