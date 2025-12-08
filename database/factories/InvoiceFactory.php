<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(50000, 500000);
        $tax = intval($subtotal * 0.1);
        $total = $subtotal + $tax;

        return [
            'number' => 'INV-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('######'),
            'invoice_date' => fake()->dateTimeBetween('-30 days'),
            'due_date' => fake()->dateTimeBetween('now', '+60 days'),
            'status' => fake()->randomElement(['draft', 'posted', 'paid', 'overdue']),
            'subtotal' => $subtotal,
            'tax_amount' => $tax,
            'total' => $total,
            'subtotal_base' => $subtotal,
            'tax_amount_base' => $tax,
            'total_base' => $total,
            'amount_paid' => 0,
        ];
    }
}
