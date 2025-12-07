<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\BomHeader;
use App\Models\BomComponent;
use App\Models\Manufacturing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create or update admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'remember_token' => Str::random(10),
                'is_admin' => true,
            ]
        );

        // Create sample products
        $products = [
            ['name' => 'Steel Frame (A1)', 'type' => 'raw_material', 'category' => 'Steel', 'price' => 500, 'cost' => 350, 'quantity' => 100],
            ['name' => 'Aluminum Plate (B2)', 'type' => 'raw_material', 'category' => 'Aluminum', 'price' => 300, 'cost' => 200, 'quantity' => 150],
            ['name' => 'Bearing Unit', 'type' => 'component', 'category' => 'Bearings', 'price' => 150, 'cost' => 80, 'quantity' => 50],
            ['name' => 'Motor Assembly (110W)', 'type' => 'component', 'category' => 'Motors', 'price' => 450, 'cost' => 280, 'quantity' => 30],
            ['name' => 'Electric Cable (50m)', 'type' => 'raw_material', 'category' => 'Cables', 'price' => 200, 'cost' => 120, 'quantity' => 75],
            ['name' => 'Finished Pump Unit', 'type' => 'finished_product', 'category' => 'Pumps', 'price' => 2500, 'cost' => 1500, 'quantity' => 10],
            ['name' => 'Control Board PCB', 'type' => 'component', 'category' => 'Electronics', 'price' => 250, 'cost' => 150, 'quantity' => 40],
            ['name' => 'Pressure Valve', 'type' => 'component', 'category' => 'Valves', 'price' => 180, 'cost' => 100, 'quantity' => 60],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['name' => $p['name']],
                $p
            );
        }

        // Create sample BOMs
        $pump = Product::where('name', 'Finished Pump Unit')->first();
        $steel = Product::where('name', 'Steel Frame (A1)')->first();
        $motor = Product::where('name', 'Motor Assembly (110W)')->first();
        $bearing = Product::where('name', 'Bearing Unit')->first();
        $cable = Product::where('name', 'Electric Cable (50m)')->first();
        $valve = Product::where('name', 'Pressure Valve')->first();

        if ($pump && $steel && $motor && $bearing) {
            $bom = BomHeader::updateOrCreate(
                ['name' => 'Standard Pump Assembly BOM'],
                [
                    'product_id' => $pump->id,
                    'quantity' => 1,
                    'total_cost' => 0,
                ]
            );

            // Clear old components
            $bom->components()->delete();

            // Add components
            $components = [
                ['product_id' => $steel->id, 'qty' => 1, 'unit_cost' => 350],
                ['product_id' => $motor->id, 'qty' => 1, 'unit_cost' => 280],
                ['product_id' => $bearing->id, 'qty' => 2, 'unit_cost' => 80],
                ['product_id' => $cable->id, 'qty' => 1, 'unit_cost' => 120],
                ['product_id' => $valve->id, 'qty' => 1, 'unit_cost' => 100],
            ];

            $total = 0;
            foreach ($components as $comp) {
                $subtotal = $comp['qty'] * $comp['unit_cost'];
                BomComponent::create([
                    'bom_id' => $bom->id,
                    'component_product_id' => $comp['product_id'],
                    'qty' => $comp['qty'],
                    'unit_cost' => $comp['unit_cost'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }

            $bom->update(['total_cost' => $total]);
        }

        // Create sample manufacturing orders
        $moData = [
            ['reference' => 'WH/MO/0001', 'product_id' => $pump->id ?? null, 'quantity' => 5, 'deadline' => now()->addDays(7), 'status' => 'draft'],
            ['reference' => 'WH/MO/0002', 'product_id' => $pump->id ?? null, 'quantity' => 3, 'deadline' => now()->addDays(14), 'status' => 'draft'],
        ];

        foreach ($moData as $mo) {
            Manufacturing::updateOrCreate(
                ['reference' => $mo['reference']],
                $mo
            );
        }

        // Employees
        $this->call(EmployeeSeeder::class);
    }
}

