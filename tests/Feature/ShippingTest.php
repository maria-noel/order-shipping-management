<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShippingTest extends TestCase
{
    // use RefreshDatabase;

    /** @test */
    public function it_can_create_a_shipping()
    {
        $order = Order::factory()->create();

        $shippingData = [
            'order_id' => $order->getId(),
            'status' => 'shipped',
            'address' => fake()->address(),
            'shipping_method' => 'standard',
            'shipment_date' =>  Carbon::now()->toDateString()
        ];

        $response = $this->postJson('/api/v1/shipments', $shippingData);

        $response->assertStatus(201);
    }

     /** @test */
     public function it_can_change_the_shipping_status()
     {
         $shipping = Shipment::factory()->create();
         $updatedShippingData = [
             'status' => 'delivered',
         ];
 
         $response = $this->json('PUT', "/api/v1/shipments/{$shipping->id}/changeStatus", $updatedShippingData);
 
         $response->assertStatus(200);

         $this->assertEquals('delivered', $shipping->order->getStatus());

     }

     /** @test */
     public function it_updates_order_status_when_shipped()
     {
        $shipping = Shipment::factory()->create();
        $updatedShippingData = [
             'status' => 'shipped',
         ];
         
         $response = $this->json('PUT', "/api/v1/shipments/{$shipping->id}/changeStatus", $updatedShippingData);

         $this->assertEquals('shipped', $shipping->order->getStatus());

     }
 
}
