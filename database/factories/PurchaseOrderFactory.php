<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(100000, 1000000);
        $tax = intval($subtotal * 0.1);
        $total = $subtotal + $tax;

        return [
            'po_number' => 'PO-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('####'),
            'order_date' => fake()->dateTimeBetween('-30 days'),
            'expected_arrival' => fake()->dateTimeBetween('now', '+30 days'),
            'status' => fake()->randomElement(['draft', 'confirmed', 'received']),
            'currency' => 'IDR',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'subtotal_base' => $subtotal,
            'tax_base' => $tax,
            'total_base' => $total,
        ];
    }
}
