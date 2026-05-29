<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    private User $client;
    private User $admin;
    private Vehicle $vehicle;
    private string $clientToken;
    private string $adminToken;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'client',  'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin',   'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'api']);

        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        $this->adminToken = JWTAuth::fromUser($this->admin);

        $this->client = User::factory()->create();
        $this->client->assignRole('client');
        $this->clientToken = JWTAuth::fromUser($this->client);

        $this->vehicle = Vehicle::factory()->create([
            'is_available'  => true,
            'price_per_day' => 100.00,
            'created_by'    => $this->admin->id,
        ]);
    }

    public function test_client_can_create_reservation(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson('/api/reservations', [
                'vehicle_id' => $this->vehicle->id,
                'start_date' => now()->addDay()->toDateString(),
                'end_date'   => now()->addDays(3)->toDateString(),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.vehicle_id', $this->vehicle->id)
            ->assertJsonPath('data.total_days', 2)
            ->assertJsonPath('data.total_price', '200.00');
    }

    public function test_overlapping_reservations_are_rejected(): void
    {
        Reservation::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'user_id'    => $this->client->id,
            'start_date' => now()->addDay()->toDateString(),
            'end_date'   => now()->addDays(5)->toDateString(),
            'status'     => 'confirmed',
        ]);

        $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson('/api/reservations', [
                'vehicle_id' => $this->vehicle->id,
                'start_date' => now()->addDays(2)->toDateString(),
                'end_date'   => now()->addDays(4)->toDateString(),
            ])->assertStatus(422)
            ->assertJsonPath('success', false);
    }

    public function test_past_date_reservation_is_rejected(): void
    {
        $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson('/api/reservations', [
                'vehicle_id' => $this->vehicle->id,
                'start_date' => now()->subDays(2)->toDateString(),
                'end_date'   => now()->subDay()->toDateString(),
            ])->assertStatus(422);
    }

    public function test_admin_can_confirm_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'user_id'    => $this->client->id,
            'status'     => 'pending',
        ]);

        $this->withHeader('Authorization', "Bearer {$this->adminToken}")
            ->postJson("/api/reservations/{$reservation->id}/confirm")
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'confirmed');
    }

    public function test_client_can_cancel_own_reservation(): void
    {
        $reservation = Reservation::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'user_id'    => $this->client->id,
            'status'     => 'pending',
        ]);

        $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson("/api/reservations/{$reservation->id}/cancel", ['reason' => 'Changed plans'])
            ->assertStatus(200)
            ->assertJsonPath('data.status', 'cancelled');
    }

    public function test_client_cannot_cancel_another_users_reservation(): void
    {
        $otherClient = User::factory()->create();
        $otherClient->assignRole('client');

        $reservation = Reservation::factory()->create([
            'vehicle_id' => $this->vehicle->id,
            'user_id'    => $otherClient->id,
            'status'     => 'pending',
        ]);

        $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson("/api/reservations/{$reservation->id}/cancel")
            ->assertStatus(403);
    }

    public function test_availability_check_returns_correct_result(): void
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->clientToken}")
            ->postJson('/api/reservations/check-availability', [
                'vehicle_id' => $this->vehicle->id,
                'start_date' => now()->addDays(10)->toDateString(),
                'end_date'   => now()->addDays(12)->toDateString(),
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.available', true);
    }
}
