<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function saveOrder(array $orderData)
    {
        // logic before save
        return Order::create($orderData);
    }

    public function showOrders()
    {
        return Order::paginate();
    }
}
