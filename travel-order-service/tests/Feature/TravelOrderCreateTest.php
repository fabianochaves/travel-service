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
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Loga o usuário (simula a autenticação)
        $this->actingAs($user);

        $data = [
            'requester_name' => 'Fabiano Chaves',
            'destination' => 'Paris',
            'departure_date' => '2025-05-01',
            'return_date' => '2025-05-10',
            'status' => 'requested',
        ];

        $response = $this->postJson('/api/travel-orders', $data);

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
        $data = [
            'requester_name' => 'Fabiano Chaves',
            'destination' => 'Paris',
            'departure_date' => '2025-05-01',
            'return_date' => '2025-05-10',
            'status' => 'requested',
        ];

        $response = $this->postJson('/api/travel-orders', $data);

        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'O Token é incompatível ou não foi informado!',
                 ]);
    }

    // Teste para criação de uma ordem de viagem com dados inválidos
    public function test_user_cannot_create_travel_order_with_invalid_data()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Loga o usuário (simula a autenticação)
        $this->actingAs($user);

        $data = [
            'requester_name' => '',  // Campo obrigatório vazio
            'destination' => 'Paris',
            'departure_date' => 'invalid-date',
            'return_date' => '2025-05-10',
            'status' => 'invalid_status',  // Status inválido
        ];

        $response = $this->postJson('/api/travel-orders', $data);

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
