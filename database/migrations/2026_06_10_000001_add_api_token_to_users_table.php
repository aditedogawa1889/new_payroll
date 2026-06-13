<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'api_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('api_token', 80)->after('password')->nullable();
            });
        }

        // Generate initial tokens for existing users
        foreach (User::all() as $user) {
            if (empty($user->api_token)) {
                $user->update([
                    'api_token' => Str::random(60)
                ]);
            }
        }

        // Now safe to add unique index
        Schema::table('users', function (Blueprint $table) {
            $table->unique('api_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('api_token');
        });
    }
};
