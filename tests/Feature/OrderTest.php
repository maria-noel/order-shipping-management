<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_order()
    {
        $user = User::factory()->create();

        $orderData['order'] = [
            'user_id' => $user->getId(), 
            'total_amount' => 850000,
            'order_date' => Carbon::now()->toDateString()
        ];
        
        $orderData['orderDetails'] = [
            [
                'product_id' => 6655,
                'quantity' => 2,
                'unit_price' => 200000,
                'subtotal' => 400000 
            ],
            [
                'product_id' => 22222,
                'quantity' => 3,
                'unit_price' => 150000,
                'subtotal' => 450000
            ],
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(201)
            ->assertJsonFragment($orderData['order']);
    }

    
    /** @test */
    public function it_can_get_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->getJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'user_id',
            'order_date',
            'total_amount',
            'created_at',
            'updated_at',
        ]);

        $this->assertNotNull($response['order_date']);
    }


    /** @test */
    public function it_can_get_all_orders()
    {
        $orders = Order::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/orders');

        $response->assertStatus(200)
            ->assertJsonCount(13);
    }


    /** @test */
    public function it_can_delete_an_order()
    {
        $order = Order::factory()->create();

        $response = $this->deleteJson("/api/v1/orders/{$order->id}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status' => 'cancelled']);
    }


}
