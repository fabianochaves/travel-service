<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TravelOrderShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Testa se o usuário pode visualizar seu próprio pedido de viagem.
     *
     * @return void
     */
    public function test_user_can_view_own_travel_order()
    {
        // Criar um usuário e um pedido associado
        $user = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->getJson("/api/travel-orders/{$travelOrder->id}");

        // Verificar se o pedido retornado é o correto
        $response->assertStatus(200);
        $response->assertJson(['travel_order' => $travelOrder->toArray()]);
    }

    /**
     * Testa se o usuário não pode visualizar o pedido de outro usuário.
     *
     * @return void
     */
    public function test_user_cannot_view_others_travel_order()
    {
        // Criar dois usuários e um pedido para o primeiro
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user1->id]);

        // Logar como o segundo usuário (tentando acessar o pedido do user1)
        $response = $this->actingAs($user2)->getJson("/api/travel-orders/{$travelOrder->id}");

        // Verificar que o usuário não pode visualizar o pedido de outro
        $response->assertStatus(403);
        $response->assertJson(['message' => 'You can only view your own travel orders.']);
    }

    /**
     * Testa se o usuário recebe um erro quando tenta visualizar uma ordem de viagem que não existe.
     *
     * @return void
     */
    public function test_user_cannot_view_non_existent_travel_order()
    {
        $user = User::factory()->create();

        $nonExistentOrderId = 999; 
        $response = $this->actingAs($user)->getJson("/api/travel-orders/{$nonExistentOrderId}");

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Order travel not found.']);
    }
}
