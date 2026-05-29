<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $client;
    private string $adminToken;
    private string $clientToken;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');

        Role::firstOrCreate(['name' => 'admin',  'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->adminToken = JWTAuth::fromUser($this->admin);

        $this->client = User::factory()->create();
        $this->client->assignRole('client');
        $this->clientToken = JWTAuth::fromUser($this->client);
    }

    public function test_anyone_can_list_vehicles(): void
    {
        Vehicle::factory()->count(3)->create(['created_by' => $this->admin->id]);

        $this->getJson('/api/vehicles')
            ->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_admin_can_create_vehicle(): void
    {
        $image = UploadedFile::fake()->image('car.jpg', 800, 600);

        $response = $this->withHeader('Authorization', "Bearer {$this->adminToken}")
            ->postJson('/api/vehicles', [
                'brand'         => 'Toyota',
                'model'         => 'Camry',
                'category'      => 'sedan',
                'transmission'  => 'automatic',
                'fuel_type'     => 'petrol',
                'price_per_day' => 75.00,
                'image'         => $image,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.brand', 'Toyota');
    }

    public function test_client_cannot_create_vehicle(): void
    {
        $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson('/api/vehicles', [
                'brand'         => 'Honda',
                'model'         => 'Civic',
                'category'      => 'sedan',
                'transmission'  => 'manual',
                'fuel_type'     => 'petrol',
                'price_per_day' => 50.00,
            ])->assertStatus(403);
    }

    public function test_admin_can_update_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['created_by' => $this->admin->id]);

        $this->withHeader('Authorization', "Bearer {$this->adminToken}")
            ->putJson("/api/vehicles/{$vehicle->id}", ['price_per_day' => 99.00])
            ->assertStatus(200)
            ->assertJsonPath('data.price_per_day', '99.00');
    }

    public function test_admin_can_soft_delete_vehicle(): void
    {
        $vehicle = Vehicle::factory()->create(['created_by' => $this->admin->id]);

        $this->withHeader('Authorization', "Bearer {$this->adminToken}")
            ->deleteJson("/api/vehicles/{$vehicle->id}")
            ->assertStatus(200);

        $this->assertSoftDeleted('vehicles', ['id' => $vehicle->id]);
    }
}
