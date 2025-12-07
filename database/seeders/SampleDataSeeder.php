<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\BomHeader;
use App\Models\BomComponent;
use App\Models\Manufacturing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SampleDataSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $products = [
            ['reference' => 'MKR-01', 'name' => 'Kroket Kentang', 'type' => 'finished_product', 'category' => 'Frozen Food', 'price' => 35000, 'cost' => 28000, 'quantity' => 20, 'uom' => 'pak'],
            ['reference' => 'MKR-02', 'name' => 'Sempol Ayam', 'type' => 'finished_product', 'category' => 'Frozen Food', 'price' => 20000, 'cost' => 15000, 'quantity' => 30, 'uom' => 'pak'],
            ['reference' => 'MKR-03', 'name' => 'Nugget Ayam', 'type' => 'finished_product', 'category' => 'Frozen Food', 'price' => 40000, 'cost' => 32000, 'quantity' => 25, 'uom' => 'pak'],
            ['reference' => 'BNBU-001', 'name' => 'Bawang Bombay', 'type' => 'raw_material', 'category' => 'Bumbu', 'price' => 4000, 'cost' => 3500, 'quantity' => 100, 'uom' => 'kg'],
            ['reference' => 'BNBU-002', 'name' => 'Bawang Merah', 'type' => 'raw_material', 'category' => 'Bumbu', 'price' => 4500, 'cost' => 4000, 'quantity' => 100, 'uom' => 'kg'],
            ['reference' => 'BNBU-003', 'name' => 'Bawang Putih', 'type' => 'raw_material', 'category' => 'Bumbu', 'price' => 4000, 'cost' => 3500, 'quantity' => 100, 'uom' => 'kg'],
            ['reference' => 'BNBU-009', 'name' => 'Tepung Terigu', 'type' => 'raw_material', 'category' => 'Bahan Kering', 'price' => 1200, 'cost' => 1000, 'quantity' => 200, 'uom' => 'kg'],
            ['reference' => 'BNBU-020', 'name' => 'Tepung Tapioka', 'type' => 'raw_material', 'category' => 'Bahan Kering', 'price' => 1500, 'cost' => 1300, 'quantity' => 200, 'uom' => 'kg'],
            ['reference' => 'BNBU-015', 'name' => 'Kuning Telur', 'type' => 'raw_material', 'category' => 'Protein', 'price' => 2800, 'cost' => 2500, 'quantity' => 120, 'uom' => 'kg'],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['reference' => $p['reference']],
                $p
            );
        }

        $semolProduct = Product::where('reference', 'MKR-02')->first();
        $nuggetProduct = Product::where('reference', 'MKR-03')->first();
        $kroketProduct = Product::where('reference', 'MKR-01')->first();

        $boms = [
            [
                'name' => 'BoM Sempol Ayam',
                'product' => $semolProduct,
                'components' => [
                    ['sku' => 'BNBU-002', 'qty' => 0.5, 'unit_cost' => 2550],
                    ['sku' => 'BNBU-003', 'qty' => 0.33, 'unit_cost' => 990],
                    ['sku' => 'BNBU-020', 'qty' => 6.67, 'unit_cost' => 1500],
                    ['sku' => 'BNBU-009', 'qty' => 1.67, 'unit_cost' => 1200],
                    ['sku' => 'BNBU-015', 'qty' => 3.34, 'unit_cost' => 2800],
                ],
            ],
            [
                'name' => 'BoM Nugget Ayam',
                'product' => $nuggetProduct,
                'components' => [
                    ['sku' => 'BNBU-003', 'qty' => 1, 'unit_cost' => 3000],
                    ['sku' => 'BNBU-001', 'qty' => 1.67, 'unit_cost' => 4000],
                    ['sku' => 'BNBU-020', 'qty' => 1.33, 'unit_cost' => 1500],
                    ['sku' => 'BNBU-009', 'qty' => 1.67, 'unit_cost' => 1200],
                ],
            ],
            [
                'name' => 'BoM Kroket Kentang',
                'product' => $kroketProduct,
                'components' => [
                    ['sku' => 'BNBU-002', 'qty' => 1.25, 'unit_cost' => 4500],
                    ['sku' => 'BNBU-003', 'qty' => 0.83, 'unit_cost' => 4000],
                    ['sku' => 'BNBU-009', 'qty' => 2.09, 'unit_cost' => 1200],
                ],
            ],
        ];

        foreach ($boms as $bomRow) {
            if (! $bomRow['product']) continue;
            $bom = BomHeader::updateOrCreate(
                ['name' => $bomRow['name']],
                [
                    'product_id' => $bomRow['product']->id,
                    'quantity' => 1,
                    'total_cost' => 0,
                ]
            );

            $bom->components()->delete();
            $total = 0;
            foreach ($bomRow['components'] as $comp) {
                $product = Product::where('reference', $comp['sku'])->first();
                $subtotal = $comp['qty'] * $comp['unit_cost'];
                BomComponent::create([
                    'bom_id' => $bom->id,
                    'component_product_id' => $product?->id,
                    'qty' => $comp['qty'],
                    'unit_cost' => $comp['unit_cost'],
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }
            $bom->update(['total_cost' => $total]);
        }

        $moData = [
            ['reference' => 'WH/MO/0101', 'product_id' => $kroketProduct?->id, 'quantity' => 50, 'deadline' => now()->addDays(7), 'status' => 'draft'],
            ['reference' => 'WH/MO/0102', 'product_id' => $nuggetProduct?->id, 'quantity' => 60, 'deadline' => now()->addDays(10), 'status' => 'draft'],
        ];

        foreach ($moData as $mo) {
            Manufacturing::updateOrCreate(['reference' => $mo['reference']], $mo);
        }
    }
}
