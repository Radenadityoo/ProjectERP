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
        $tax = intval($subtotal * 0.1);
        $total = $subtotal + $tax;

        return [
            'so_number' => 'SO-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('####'),
            'order_date' => fake()->dateTimeBetween('-30 days'),
            'delivery_date' => fake()->dateTimeBetween('now', '+30 days'),
            'status' => fake()->randomElement(['draft', 'confirmed', 'shipped', 'delivered']),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total' => $total,
            'subtotal_base' => $subtotal,
            'tax_amount_base' => $tax,
            'total_base' => $total,
        ];
    }
}
