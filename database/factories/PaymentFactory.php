<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $amount = fake()->numberBetween(50000, 500000);

        return [
            'payment_number' => 'PMT-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('####'),
            'payment_date' => fake()->dateTimeBetween('-30 days'),
            'payment_method' => fake()->randomElement(['bank_transfer', 'cash', 'credit_card', 'giro']),
            'amount' => $amount,
            'amount_base' => $amount,
            'status' => 'received',
        ];
    }
}
