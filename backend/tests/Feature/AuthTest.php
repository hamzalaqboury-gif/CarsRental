<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::firstOrCreate(['name' => 'client', 'guard_name' => 'api']);
        Role::firstOrCreate(['name' => 'admin',  'guard_name' => 'api']);
    }

    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name'                  => 'Test User',
            'email'                 => 'test@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success', 'data' => ['user', 'token'],
            ]);
    }

    public function test_registration_requires_valid_email(): void
    {
        $this->postJson('/api/auth/register', [
            'name'                  => 'Test',
            'email'                 => 'not-an-email',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
        ])->assertStatus(422)->assertJsonPath('success', false);
    }

    public function test_registration_prevents_duplicate_email(): void
    {
        User::factory()->create(['email' => 'dup@example.com']);

        $this->postJson('/api/auth/register', [
            'name'                  => 'Another',
            'email'                 => 'dup@example.com',
            'password'              => 'Password@123',
            'password_confirmation' => 'Password@123',
        ])->assertStatus(422);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Password@123')]);
        $user->assignRole('client');

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'Password@123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_login_rejects_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertStatus(401);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $user = User::factory()->create();
        $user->assignRole('client');

        $token = \Tymon\JWTAuth\Facades\JWTAuth::fromUser($user);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/auth/me')
            ->assertStatus(200)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_unauthenticated_request_is_rejected(): void
    {
        $this->getJson('/api/auth/me')
            ->assertStatus(401);
    }
}
