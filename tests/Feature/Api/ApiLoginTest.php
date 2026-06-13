<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_login_with_valid_email_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
            'api_token' => 'existing-token-xyz'
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'test@example.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'api_token' => 'existing-token-xyz',
                'user' => [
                    'email' => 'test@example.com',
                    'api_token' => 'existing-token-xyz'
                ]
            ]);
    }

    public function test_can_login_with_valid_username_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('secret123'),
            'api_token' => 'existing-token-xyz'
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'testuser',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'api_token' => 'existing-token-xyz'
            ]);
    }

    public function test_can_login_with_explicit_username_field(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => Hash::make('secret123'),
            'api_token' => 'existing-token-xyz'
        ]);

        $response = $this->postJson('/api/login', [
            'username' => 'testuser',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'api_token' => 'existing-token-xyz'
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret123')
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
    }

    public function test_generates_token_on_login_if_empty(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('secret123'),
            'api_token' => null
        ]);

        $response = $this->postJson('/api/login', [
            'login' => 'test@example.com',
            'password' => 'secret123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['api_token']);
        $this->assertEquals(60, strlen($responseData['api_token']));

        $user->refresh();
        $this->assertEquals($responseData['api_token'], $user->api_token);
    }
}
