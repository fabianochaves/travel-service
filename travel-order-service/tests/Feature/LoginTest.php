<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    use WithFaker;
    use DatabaseTransactions; // Use este trait para isolar as transações

    /**
     * Testa se um usuário pode fazer login com credenciais válidas.
     */
    public function test_user_can_login_with_valid_credentials()
    {
        // Criação do usuário para o teste
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Garanta que a senha seja a mesma usada no teste
        ]);

        // Simulação de uma requisição de login
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assertivas
        $response->assertStatus(200)
            ->assertJson(fn ($json) =>
                $json->has('token') // Verifica se a resposta contém o token
                     ->etc()
            );
    }

    /**
     * Testa se o login falha com credenciais inválidas.
     */
    public function test_user_cannot_login_with_invalid_credentials()
    {
        // Criação do usuário para o teste
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'), // Garanta que a senha seja a mesma usada no teste
        ]);

        // Simulação de uma requisição de login com credenciais inválidas
        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assertivas
        $response->assertStatus(401)
            ->assertJson(fn ($json) =>
                $json->where('error', 'Unauthorized') // Verifica se a mensagem de erro é retornada
                     ->etc()
            );
    }
}
