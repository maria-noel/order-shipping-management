<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class orderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(1,4),
            'product_id' => fake()->numberBetween(2500, 10000),
            'quantity' => fake()->numberBetween(1,10),
            'unit_price' => fake()->numberBetween(500000, 100000),
            'subtotal' => fake()->numberBetween(1, 1000000)
        ];
    }
}
