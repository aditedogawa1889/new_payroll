<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_unauthorized_without_token(): void
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized. Access token is missing.'
            ]);
    }

    public function test_api_unauthorized_with_invalid_token(): void
    {
        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer invalid-token'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Unauthorized. Access token is invalid.'
            ]);
    }

    public function test_api_authorized_with_valid_bearer_token(): void
    {
        $user = User::factory()->create([
            'api_token' => 'test-bearer-token-123456'
        ]);

        $response = $this->getJson('/api/user', [
            'Authorization' => 'Bearer test-bearer-token-123456'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'api_token' => 'test-bearer-token-123456'
            ]);
    }

    public function test_api_authorized_with_valid_x_api_token_header(): void
    {
        $user = User::factory()->create([
            'api_token' => 'test-header-token-123456'
        ]);

        $response = $this->getJson('/api/user', [
            'X-API-TOKEN' => 'test-header-token-123456'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'api_token' => 'test-header-token-123456'
            ]);
    }

    public function test_api_authorized_with_valid_query_parameter(): void
    {
        $user = User::factory()->create([
            'api_token' => 'test-query-token-123456'
        ]);

        $response = $this->getJson('/api/user?api_token=test-query-token-123456');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'email' => $user->email,
                'api_token' => 'test-query-token-123456'
            ]);
    }
}
