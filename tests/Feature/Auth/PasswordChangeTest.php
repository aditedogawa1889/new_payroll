<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordChangeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_must_change_password_is_redirected_to_profile(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('profile.edit'));
    }

    public function test_user_who_changed_password_can_access_dashboard(): void
    {
        $user = User::factory()->create([
            'must_change_password' => true,
        ]);

        $response = $this->actingAs($user)->put('/password', [
            'current_password' => 'password',
            'password' => 'N@wPassword123',
            'password_confirmation' => 'N@wPassword123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertFalse($user->fresh()->must_change_password);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_can_reset_user_password(): void
    {
        $admin = User::factory()->create([
            'must_change_password' => false,
        ]);
        $userToReset = User::factory()->create([
            'must_change_password' => false,
            'password' => bcrypt('old-password'),
        ]);

        $response = $this->actingAs($admin)->post(route('users.reset-password', $userToReset));

        $response->assertRedirect(route('users.index'));
        $userToReset->refresh();
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Metland@123', $userToReset->password));
        $this->assertTrue($userToReset->must_change_password);
    }
}
