<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TravelOrderCreateTest extends TestCase
{
    use DatabaseTransactions;

    // Teste para criação de uma ordem de viagem com dados válidos
    public function test_user_can_create_travel_order_with_valid_data()
    {
        // Cria um usuário
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Loga o usuário (simula a autenticação)
        $this->actingAs($user);

        // Dados válidos para a criação da ordem de viagem
        $data = [
            'requester_name' => 'Fabiano Chaves',
            'destination' => 'Paris',
            'departure_date' => '2025-05-01',
            'return_date' => '2025-05-10',
            'status' => 'requested',
        ];

        // Chama o endpoint de criação da ordem de viagem
        $response = $this->postJson('/api/travel-orders', $data);

        // Verifica se o status é 201 (Created) e se os dados retornados são corretos
        $response->assertStatus(201)
                 ->assertJson([
                     'travel_order' => $data,
                 ]);

        // Verifica se a ordem foi criada no banco de dados
        $this->assertDatabaseHas('travel_orders', [
            'requester_name' => 'Fabiano Chaves',
            'destination' => 'Paris',
            'departure_date' => '2025-05-01',
            'return_date' => '2025-05-10',
            'status' => 'requested',
            'user_id' => $user->id,
        ]);
    }

    // Teste para tentativa de criação de ordem de viagem sem estar autenticado
    public function test_user_cannot_create_travel_order_without_authentication()
    {
        // Dados válidos para a criação da ordem de viagem
        $data = [
            'requester_name' => 'Fabiano Chaves',
            'destination' => 'Paris',
            'departure_date' => '2025-05-01',
            'return_date' => '2025-05-10',
            'status' => 'requested',
        ];

        // Chama o endpoint de criação da ordem de viagem sem autenticação
        $response = $this->postJson('/api/travel-orders', $data);

        // Verifica se o status é 401 (Unauthorized) quando o usuário não está autenticado
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthenticated.',
                 ]);
    }

    // Teste para criação de uma ordem de viagem com dados inválidos
    public function test_user_cannot_create_travel_order_with_invalid_data()
    {
        // Cria um usuário
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Loga o usuário (simula a autenticação)
        $this->actingAs($user);

        // Dados inválidos (campo "departure_date" está no futuro e "status" é inválido)
        $data = [
            'requester_name' => '',  // Campo obrigatório vazio
            'destination' => 'Paris',
            'departure_date' => 'invalid-date',
            'return_date' => '2025-05-10',
            'status' => 'invalid_status',  // Status inválido
        ];

        // Chama o endpoint de criação da ordem de viagem
        $response = $this->postJson('/api/travel-orders', $data);

        // Verifica se o status é 400 (Bad Request) e se os erros de validação estão presentes
        $response->assertStatus(400)
                 ->assertJsonStructure([
                     'errors' => [
                         'requester_name',
                         'departure_date',
                         'status',
                     ],
                 ]);
    }
}
