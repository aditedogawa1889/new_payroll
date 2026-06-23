<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->after('name');
            });
        }

        // Update existing users' usernames using the prefix of their email
        $users = User::all();
        foreach ($users as $user) {
            if (empty($user->username)) {
                $emailPart = explode('@', $user->email)[0];
                $baseUsername = $emailPart;
                $counter = 1;
                while (User::where('username', $baseUsername)->exists()) {
                    $baseUsername = $emailPart . $counter;
                    $counter++;
                }
                $user->username = $baseUsername;
                $user->save();
            }
        }

        // Now add the unique constraint
        // We check if the unique index already exists by trying to add it or catching exception
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('username');
            });
        } catch (\Exception $e) {
            // Index might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                // Drop unique index first
                try {
                    $table->dropUnique(['username']);
                } catch (\Exception $e) {}
                $table->dropColumn('username');
            });
        }
    }
};
