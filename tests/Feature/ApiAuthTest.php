<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_valid_credentials_returns_token(): void
    {
        $user = User::factory()->create([
            'email' => 'tester@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'tester@example.com',
            'password' => 'secret123',
            'device_name' => 'phpunit',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user' => ['id', 'name', 'email', 'role']]);
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        User::factory()->create(['email' => 'a@b.test', 'password' => Hash::make('correct')]);

        $response = $this->postJson('/api/login', [
            'email' => 'a@b.test',
            'password' => 'wrong',
            'device_name' => 'phpunit',
        ]);

        $response->assertStatus(422);
    }

    public function test_login_rejects_banned_user(): void
    {
        User::factory()->create([
            'email' => 'banned@test.com',
            'password' => Hash::make('pw'),
            'banned_at' => now(),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'banned@test.com',
            'password' => 'pw',
            'device_name' => 'phpunit',
        ]);

        $response->assertStatus(422);
    }

    public function test_user_endpoint_requires_auth(): void
    {
        $this->getJson('/api/user')->assertStatus(401);
    }

    public function test_lots_endpoint_is_public(): void
    {
        $this->getJson('/api/lots')->assertStatus(200)->assertJsonStructure(['data', 'links', 'meta']);
    }
}
