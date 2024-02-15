<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipments>
 */
class ShipmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'address' => fake()->address(),
            'shipping_method' => fake()->randomElement(['standard', 'express']),
            'shipment_date' => fake()->date(),
            'status' => fake()->randomElement(['pending', 'shipped', 'delivered']),
        ];
    }
}
