<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se um usuário pode fazer login com credenciais válidas.
    */
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assertivas
        $response->assertStatus(200)
            ->assertJson(fn ($json) =>
                $json->has('token')
                     ->etc()
            );
    }

    /**
     * Testa se o login falha com credenciais inválidas.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assertivas
        $response->assertStatus(401)
            ->assertJson(fn ($json) =>
                $json->where('error', 'Unauthorized')
                     ->etc()
            );
    }
}
