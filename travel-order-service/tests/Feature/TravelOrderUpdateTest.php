<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Models\TravelOrder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Carbon\Carbon;

class TravelOrderUpdateTest extends TestCase
{
    use DatabaseTransactions;

    // Testa se o mesmo usuário não pode atualizar seu próprio pedido
    public function test_user_cannot_update_own_travel_order()
    {
        // Crie dois usuários e autentique o primeiro
        $user1 = User::factory()->create(); // O usuário que vai tentar atualizar
        $user2 = User::factory()->create(); // Outro usuário

        // Crie uma TravelOrder associada ao user1 (o usuário que tentará atualizar)
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user1->id]);

        // O usuário 1 não deve ser capaz de atualizar o próprio pedido
        $response = $this->actingAs($user1)->putJson("/api/travel-orders/{$travelOrder->id}", [
            'status' => 'approved',
        ]);

        // Espera-se que o status seja 403, pois o mesmo usuário não pode atualizar seu pedido
        $response->assertStatus(403);
        $response->assertJson(['message' => 'You can only update your own travel orders.']);
    }

    // Testa se outro usuário pode atualizar o pedido de um usuário diferente
    public function test_user_can_update_other_users_travel_order()
    {
        // Crie dois usuários e autentique o segundo
        $user1 = User::factory()->create(); // O usuário dono do pedido
        $user2 = User::factory()->create(); // Outro usuário

        // Crie uma TravelOrder associada ao user1
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user1->id]);

        // O usuário 2 (outro usuário) deve poder atualizar o pedido do usuário 1
        $response = $this->actingAs($user2)->putJson("/api/travel-orders/{$travelOrder->id}", [
            'status' => 'approved',
        ]);

        // O status deve ser 200 porque o usuário 2 pode atualizar o pedido de outro usuário
        $response->assertStatus(200);
        $response->assertJson(['travel_order' => $travelOrder->fresh()->toArray()]);
    }

    // Testa se o usuário não pode atualizar a TravelOrder com status inválido
    public function test_user_cannot_update_travel_order_with_invalid_status()
    {
        $user = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->putJson("/api/travel-orders/{$travelOrder->id}", [
            'status' => 'invalid_status', // Status inválido
        ]);

        $response->assertStatus(400);
        $response->assertJsonStructure(['errors']);
    }

    // Testa se o usuário não pode cancelar a TravelOrder após o dia de criação
    public function test_user_cannot_cancel_travel_order_after_creation_day()
    {
        $user = User::factory()->create();
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user->id]);

        // Avance o tempo para um dia após a criação
        $travelOrder->created_at = now()->subDay();
        $travelOrder->save();

        $response = $this->actingAs($user)->putJson("/api/travel-orders/{$travelOrder->id}", [
            'status' => 'canceled',
        ]);

        $response->assertStatus(400);
        $response->assertJson(['message' => 'You can only cancel a travel order on the same day it was created.']);
    }

    // Testa se o usuário pode cancelar a TravelOrder no mesmo dia
    public function test_user_can_update_travel_order_status_to_canceled_on_same_day_by_other_user()
    {
        // Criar dois usuários
        $user1 = User::factory()->create(); // Dono do pedido
        $user2 = User::factory()->create(); // Outro usuário tentando cancelar
    
        // Criar um pedido para o user1
        $travelOrder = TravelOrder::factory()->create(['user_id' => $user1->id]);
    
        // Verificar se o pedido foi criado no mesmo dia
        $this->assertTrue(Carbon::parse($travelOrder->created_at)->isToday());
    
        // Tentar cancelar o pedido com o user2 (outro usuário)
        $response = $this->actingAs($user2)->putJson("/api/travel-orders/{$travelOrder->id}", [
            'status' => 'canceled',
        ]);
    
        // Verificar se o pedido foi cancelado corretamente
        $response->assertStatus(200);
        $response->assertJson(['travel_order' => $travelOrder->fresh()->toArray()]);
    }
    
    
}
