<?php

namespace Database\Factories;

use App\Models\SalesOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesOrderFactory extends Factory
{
    protected $model = SalesOrder::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100000, 1000000);
        $tax_amount = intval($subtotal * 0.1);
        $total = $subtotal + $tax_amount;

        return [
            'so_number' => 'SO-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('####'),
            'customer_id' => fake()->numberBetween(1, 10),
            'order_date' => fake()->dateTimeBetween('-30 days'),
            'delivery_date' => fake()->dateTimeBetween('now', '+30 days'),
            'status' => fake()->randomElement(['draft', 'confirmed', 'shipped', 'delivered']),
            'subtotal' => $subtotal,
            'tax_amount' => $tax_amount,
            'total' => $total,
        ];
    }
}
