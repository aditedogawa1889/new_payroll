<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop spatie pivot tables yang bergantung pada permissions dan roles terlebih dahulu
        // (urutan penting untuk FK di SQL Server)
        $this->dropTableIfExists('role_has_permissions');
        $this->dropTableIfExists('model_has_permissions');
        $this->dropTableIfExists('model_has_roles');

        // Sekarang drop tabel yang ada FK ke permissions/roles
        // Cek dan drop FK di tabel menus jika ada
        if (Schema::hasColumn('menus', 'permission_id')) {
            Schema::table('menus', function (Blueprint $table) {
                // Drop foreign key ke permissions jika ada
                try {
                    $table->dropForeign(['permission_id']);
                } catch (\Exception $e) {
                    // Tidak ada FK, lanjutkan
                }
                $table->dropColumn('permission_id');
            });
        }

        // Sekarang aman untuk drop roles dan permissions
        $this->dropTableIfExists('roles');
        $this->dropTableIfExists('permissions');

        // Buat ulang tabel permissions kustom
        Schema::create('permissions', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('description', 255);
            $table->timestamps();
        });

        // Insert data awal
        DB::table('permissions')->insert([
            ['id' => 0,  'description' => 'External Agent',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 99, 'description' => 'Admin IT Pusat',   'created_at' => now(), 'updated_at' => now()],
            ['id' => 1,  'description' => 'Admin Payroll',    'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'description' => 'Manager Payroll',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }

    private function dropTableIfExists(string $table): void
    {
        if (Schema::hasTable($table)) {
            // Di SQL Server, perlu drop FK dulu secara manual jika ada
            Schema::drop($table);
        }
    }
};
