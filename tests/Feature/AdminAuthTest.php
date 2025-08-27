<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login with valid credentials.
     */
    public function test_admin_can_login_with_valid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'is_admin',
                ],
            ]);
    }

    /**
     * Test admin login with invalid credentials.
     */
    public function test_admin_cannot_login_with_invalid_credentials(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test non-admin user cannot login to admin panel.
     */
    public function test_non_admin_user_cannot_login_to_admin_panel(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(403);
    }

    /**
     * Test admin registration with valid data and secret.
     */
    public function test_admin_can_register_with_valid_data_and_secret(): void
    {
        // Set admin secret in config for testing
        config(['auth.admin_secret' => 'test-admin-secret']);

        $response = $this->postJson('/api/admin/register', [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin_secret' => 'test-admin-secret',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'is_admin',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newadmin@example.com',
            'is_admin' => true,
        ]);
    }

    /**
     * Test admin registration with invalid secret.
     */
    public function test_admin_cannot_register_with_invalid_secret(): void
    {
        // Set admin secret in config for testing
        config(['auth.admin_secret' => 'test-admin-secret']);

        $response = $this->postJson('/api/admin/register', [
            'name' => 'New Admin',
            'email' => 'newadmin@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'admin_secret' => 'wrong-secret',
        ]);

        $response->assertStatus(403);

        $this->assertDatabaseMissing('users', [
            'email' => 'newadmin@example.com',
        ]);
    }

    /**
     * Test admin logout.
     */
    public function test_admin_can_logout(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $loginResponse = $this->postJson('/api/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $token = $loginResponse->json('token');

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/admin/logout');

        $response->assertStatus(200);

        // Verify token is invalidated by trying to access a protected route
        $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/admin/user')
            ->assertStatus(401);
    }

    /**
     * Test admin middleware blocks non-admin users.
     */
    public function test_admin_middleware_blocks_non_admin_users(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        $this->actingAs($user);

        $response = $this->getJson('/api/admin/user');

        $response->assertStatus(403);
    }

    /**
     * Test admin middleware allows admin users.
     */
    public function test_admin_middleware_allows_admin_users(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        $this->actingAs($admin);

        $response = $this->getJson('/api/admin/user');

        $response->assertStatus(200)
            ->assertJson([
                'id' => $admin->id,
                'email' => 'admin@example.com',
                'is_admin' => true,
            ]);
    }
}