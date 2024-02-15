<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase; 
    /** @test */
    public function it_can_generate_an_order_with_order_details()
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

        $orderObj = new Order();
        $order = $orderObj->generateOrder($orderData);

        $this->assertDatabaseHas('orders', $orderData['order']);

        foreach ($orderData['orderDetails'] as $orderDetailData) {
            $this->assertDatabaseHas('order_details', $orderDetailData);
        }

        $this->assertCount(count($orderData['orderDetails']), $order->orderDetails);

    }

     /** @test */
     public function it_can_show_orders()
     {
         $orders = Order::factory()->count(3)->create();
 
         $orderRepository = new OrderRepository();
         $shownOrders = $orderRepository->showOrders();
 
         $this->assertEquals($orders->count(), count($shownOrders));
 
         foreach ($orders as $order) {
             $this->assertContains($order->id, $shownOrders->pluck('id'));
         }
     }

       /** @test */
    public function it_can_show_an_order_with_order_details()
    {
        $order = Order::factory()->create();
        $orderDetails = OrderDetail::factory()->count(3)->create(['order_id' => $order->id]);

        $shownOrder = Order::showOrder($order->id);

        $this->assertEquals($order->id, $shownOrder->id);
        $this->assertEquals($order->total_amount, $shownOrder->total_amount);
        $this->assertCount(3, $shownOrder->orderDetails);
    }
}
