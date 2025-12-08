<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' ' . fake()->word(),
            'type' => 'product',
            'track_inventory' => true,
            'price' => fake()->numberBetween(10000, 500000),
            'cost' => fake()->numberBetween(5000, 250000),
            'category' => fake()->word(),
            'reference' => 'REF-' . strtoupper(fake()->unique()->bothify('????-####')),
            'barcode' => fake()->unique()->ean13(),
            'internal_notes' => fake()->sentence(),
        ];
    }
}
