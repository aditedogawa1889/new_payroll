<?php

namespace Tests\Feature\Api;

use App\Models\MasterIntegrationType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HrisIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed basic data needed for tests
        $this->seed(\Database\Seeders\MasterIntegrationTypeSeeder::class);
        
        // Create roles
        Role::create(['name' => 'hris-integration']);
    }

    public function test_can_store_integration_with_valid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('hris-integration');
        Sanctum::actingAs($user);

        $payload = [
            'id_integ_type' => 1,
            'emp_number' => 123456,
            'employee_id' => 'EMP001',
            'job_title_id' => 5,
            'job_title_future_id' => 7,
            'join_date' => '2024-01-15',
            'location_from_id' => 2,
            'location_to_id' => 3,
            'effective_date' => '2024-02-01',
        ];

        $response = $this->postJson('/api/hris/integrations', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Integration stored',
            ])
            ->assertJsonStructure(['message', 'integration_id']);

        $this->assertDatabaseHas('employee_integrations', [
            'emp_number' => 123456,
            'employee_id' => 'EMP001',
            'is_processed' => 0,
        ]);
    }

    public function test_fails_validation_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $user->assignRole('hris-integration');
        Sanctum::actingAs($user);

        // Empty payload
        $response = $this->postJson('/api/hris/integrations', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_integ_type', 'emp_number', 'employee_id', 'job_title_id', 'join_date', 'location_from_id', 'location_to_id', 'effective_date']);

        // Invalid types
        $payload = [
            'id_integ_type' => 999, // Non-existent
            'emp_number' => 'not-a-number',
            'join_date' => 'not-a-date',
            'effective_date' => 'not-a-date',
        ];

        $response = $this->postJson('/api/hris/integrations', $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['id_integ_type', 'emp_number', 'join_date', 'effective_date']);
    }

    public function test_fails_if_effective_date_is_before_join_date(): void
    {
        $user = User::factory()->create();
        $user->assignRole('hris-integration');
        Sanctum::actingAs($user);

        $payload = [
            'id_integ_type' => 1,
            'emp_number' => 123456,
            'employee_id' => 'EMP001',
            'job_title_id' => 5,
            'join_date' => '2024-01-15',
            'location_from_id' => 2,
            'location_to_id' => 3,
            'effective_date' => '2024-01-10', // Invalid
        ];

        $response = $this->postJson('/api/hris/integrations', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['effective_date']);
    }

    public function test_unauthorized_if_no_token(): void
    {
        $response = $this->postJson('/api/hris/integrations', []);

        $response->assertStatus(401);
    }

    public function test_forbidden_if_no_role(): void
    {
        $user = User::factory()->create();
        // No role assigned
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/hris/integrations', []);

        $response->assertStatus(403);
    }
}
