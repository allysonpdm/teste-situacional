<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_success()
    {
        $password = 'password';
        $user = User::factory()->create([
            'password' => Hash::make($password),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'expires_in',
                'access_token',
                'token_type',
            ]);
    }

    public function test_login_failure_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Authentication failed.',
            ]);
    }

    public function test_logout_success()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->postJson('/api/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged out.',
            ]);
    }

    public function test_refresh_success()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->postJson('/api/refresh', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'expires_in',
                'access_token',
                'token_type',
            ]);
    }

    public function test_me_success()
    {
        $user = User::factory()->create();
        $token = Auth::login($user);

        $response = $this->getJson('/api/me', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
