<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TravelOrderIndexTest extends TestCase
{
    use DatabaseTransactions;

    // Método para criar um usuário e autenticar para os testes
    private function authenticateUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Autenticando o usuário para a requisição
        return $user;
    }

    // Teste para verificar o retorno quando o status é inválido
    public function test_invalid_status()
    {
        $user = \App\Models\User::factory()->create();
    
        $response = $this->actingAs($user)->get('/api/travel-orders?status=invalid_status');
    
        $response->assertStatus(400)
                 ->assertJson([
                     'message' => 'Invalid status provided.'
                 ]);
    }

    // Teste para verificar o formato inválido da data
    public function test_invalid_departure_date_format()
    {
        $user = \App\Models\User::factory()->create();
    
        $response = $this->actingAs($user)->get('/api/travel-orders?departure_date=2025-03-05');

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
                 ->assertJson(['message' => 'Invalid departure_date format. Use dd/mm/YYYY.']);
    }

 
    // Teste para verificar o filtro por destino
    public function test_filter_by_destination()
    {
        $user = $this->authenticateUser();

        // Criar registros com destinos diferentes
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'requested',
            'departure_date' => '2025-03-05',
            'return_date' => '2025-03-08',
            'destination' => 'Salvador BA',
        ]);
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'approved',
            'departure_date' => '2025-03-06',
            'return_date' => '2025-03-20',
            'destination' => 'Recife PE',
        ]);

        $response = $this->getJson('/api/travel-orders', [
            'destination' => 'Salvador BA'
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonFragment(['destination' => 'Salvador BA']);
    }

    // Teste para verificar o filtro por status
    public function test_filter_by_status()
    {
        $user = $this->authenticateUser();

        // Criar registros com status diferentes
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'requested',
            'departure_date' => '2025-03-05',
            'return_date' => '2025-03-06',
            'destination' => 'Salvador BA',
        ]);
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'approved',
            'departure_date' => '2025-03-06',
            'return_date' => '2025-03-08',
            'destination' => 'Recife PE',
        ]);

        $response = $this->getJson('/api/travel-orders', [
            'status' => 'requested'
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonFragment(['status' => 'requested']);
    }

    // Teste para verificar o filtro por intervalo de datas
    public function test_filter_by_date_range()
    {
        $user = $this->authenticateUser(); // Autentica o usuário

        // Criar registros com datas diferentes
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'requested',
            'departure_date' => '2025-03-05',
            'return_date' => '2025-03-12',
            'destination' => 'Salvador BA',
        ]);
        TravelOrder::create([
            'user_id' => $user->id,
            "requester_name" => "Fabiano Chaves",
            'status' => 'approved',
            'departure_date' => '2025-03-10',
            'return_date' => '2025-03-15',
            'destination' => 'Recife PE',
        ]);

        $response = $this->getJson('/api/travel-orders', [
            'start_date' => '05/03/2025',
            'end_date' => '06/03/2025'
        ]);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJsonFragment(['departure_date' => '2025-03-05']);
    }
}
