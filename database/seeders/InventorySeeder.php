<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Receipt;
use App\Models\Delivery;
use App\Models\Transfer;
use App\Models\Adjustment;
use App\Models\StockMovement;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InventorySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Enable inventory tracking for all products
        Product::query()->update(['track_inventory' => true]);

        // Get products
        $rawMaterials = Product::where('type', 'raw_material')->get();
        $finishedProducts = Product::where('type', 'finished_product')->get();

        // Reset quantities to 0 for fresh start
        Product::query()->update(['quantity' => 0]);

        // =====================
        // 1. CREATE RECEIPTS (Incoming inventory from suppliers)
        // =====================
        
        // Receipt 1: Initial raw material purchase
        $receipt1 = Receipt::create([
            'reference' => 'RCP-2025-001',
            'supplier' => 'PT Bahan Baku Indonesia',
            'received_at' => now()->subDays(10),
        ]);

        $receiptItems1 = [
            ['sku' => 'BNBU-001', 'qty' => 100],  // Bawang Bombay
            ['sku' => 'BNBU-002', 'qty' => 100],  // Bawang Merah
            ['sku' => 'BNBU-003', 'qty' => 100],  // Bawang Putih
        ];

        foreach ($receiptItems1 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->increment('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'receipt',
                    'reference_id' => $receipt1->id,
                    'reference_type' => 'Receipt',
                    'qty_in' => $item['qty'],
                    'qty_out' => 0,
                    'notes' => "Received from {$receipt1->supplier}",
                    'created_at' => $receipt1->received_at,
                ]);
            }
        }

        // Receipt 2: Dry goods and protein materials
        $receipt2 = Receipt::create([
            'reference' => 'RCP-2025-002',
            'supplier' => 'CV Tepung Berkualitas',
            'received_at' => now()->subDays(8),
        ]);

        $receiptItems2 = [
            ['sku' => 'BNBU-009', 'qty' => 200],  // Tepung Terigu
            ['sku' => 'BNBU-020', 'qty' => 200],  // Tepung Tapioka
            ['sku' => 'BNBU-015', 'qty' => 120],  // Kuning Telur
        ];

        foreach ($receiptItems2 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->increment('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'receipt',
                    'reference_id' => $receipt2->id,
                    'reference_type' => 'Receipt',
                    'qty_in' => $item['qty'],
                    'qty_out' => 0,
                    'notes' => "Received from {$receipt2->supplier}",
                    'created_at' => $receipt2->received_at,
                ]);
            }
        }

        // =====================
        // 2. CREATE MANUFACTURING PRODUCTION (Simulated by adjustments)
        // =====================
        
        $adjustment1 = Adjustment::create([
            'reference' => 'ADJ-2025-001',
            'reason' => 'Manufacturing Production - Kroket Kentang Batch 1',
            'adjusted_at' => now()->subDays(5),
        ]);

        // Simulate components used
        $kroketComponents = [
            ['sku' => 'BNBU-002', 'qty' => 50],   // Bawang Merah
            ['sku' => 'BNBU-003', 'qty' => 33],   // Bawang Putih
            ['sku' => 'BNBU-009', 'qty' => 104],  // Tepung Terigu
        ];

        foreach ($kroketComponents as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->decrement('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'adjustment',
                    'reference_id' => $adjustment1->id,
                    'reference_type' => 'Adjustment',
                    'qty_in' => 0,
                    'qty_out' => $item['qty'],
                    'notes' => "Components used for Kroket Kentang production",
                    'created_at' => $adjustment1->adjusted_at,
                ]);
            }
        }

        // Add finished product
        $kroketProduct = Product::where('reference', 'MKR-01')->first();
        if ($kroketProduct) {
            $kroketProduct->increment('quantity', 40);
            StockMovement::create([
                'product_id' => $kroketProduct->id,
                'movement_type' => 'adjustment',
                'reference_id' => $adjustment1->id,
                'reference_type' => 'Adjustment',
                'qty_in' => 40,
                'qty_out' => 0,
                'notes' => "Kroket Kentang produced - Batch 1",
                'created_at' => $adjustment1->adjusted_at,
            ]);
        }

        // =====================
        // 3. CREATE DELIVERIES (Sales/Outbound)
        // =====================
        
        $delivery1 = Delivery::create([
            'reference' => 'DEL-2025-001',
            'customer' => 'PT Retail Mart Jaya',
            'shipped_at' => now()->subDays(3),
        ]);

        $deliveryItems1 = [
            ['sku' => 'MKR-01', 'qty' => 10],  // Kroket Kentang
            ['sku' => 'MKR-02', 'qty' => 15],  // Sempol Ayam
        ];

        foreach ($deliveryItems1 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->decrement('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'delivery',
                    'reference_id' => $delivery1->id,
                    'reference_type' => 'Delivery',
                    'qty_in' => 0,
                    'qty_out' => $item['qty'],
                    'notes' => "Delivered to {$delivery1->customer}",
                    'created_at' => $delivery1->shipped_at,
                ]);
            }
        }

        $delivery2 = Delivery::create([
            'reference' => 'DEL-2025-002',
            'customer' => 'Toko Kelontong Semarang',
            'shipped_at' => now()->subDays(1),
        ]);

        $deliveryItems2 = [
            ['sku' => 'MKR-03', 'qty' => 8],   // Nugget Ayam
            ['sku' => 'MKR-02', 'qty' => 10],  // Sempol Ayam
        ];

        foreach ($deliveryItems2 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->decrement('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'delivery',
                    'reference_id' => $delivery2->id,
                    'reference_type' => 'Delivery',
                    'qty_in' => 0,
                    'qty_out' => $item['qty'],
                    'notes' => "Delivered to {$delivery2->customer}",
                    'created_at' => $delivery2->shipped_at,
                ]);
            }
        }

        // =====================
        // 4. CREATE TRANSFERS (Between locations)
        // =====================
        
        $transfer1 = Transfer::create([
            'reference' => 'TRF-2025-001',
            'from_location' => 'Warehouse Jakarta',
            'to_location' => 'Branch Surabaya',
            'transferred_at' => now()->subDays(2),
        ]);

        $transferItems1 = [
            ['sku' => 'MKR-01', 'qty' => 5],   // Kroket Kentang
            ['sku' => 'MKR-03', 'qty' => 8],   // Nugget Ayam
        ];

        foreach ($transferItems1 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'transfer',
                    'reference_id' => $transfer1->id,
                    'reference_type' => 'Transfer',
                    'qty_in' => 0,
                    'qty_out' => 0,
                    'notes' => "Transferred from {$transfer1->from_location} to {$transfer1->to_location}",
                    'created_at' => $transfer1->transferred_at,
                ]);
            }
        }

        // =====================
        // 5. CREATE ADJUSTMENTS (Discrepancies/Damage)
        // =====================
        
        $adjustment2 = Adjustment::create([
            'reference' => 'ADJ-2025-002',
            'reason' => 'Physical count discrepancy - Damaged goods during inspection',
            'adjusted_at' => now()->subDays(1),
        ]);

        $adjustmentItems = [
            ['sku' => 'MKR-02', 'qty' => -3],   // 3 Sempol Ayam damaged
            ['sku' => 'BNBU-001', 'qty' => 5],  // 5 kg Bawang Bombay found
        ];

        foreach ($adjustmentItems as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                if ($item['qty'] > 0) {
                    $product->increment('quantity', $item['qty']);
                    $qtyIn = $item['qty'];
                    $qtyOut = 0;
                } else {
                    $product->decrement('quantity', abs($item['qty']));
                    $qtyIn = 0;
                    $qtyOut = abs($item['qty']);
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'adjustment',
                    'reference_id' => $adjustment2->id,
                    'reference_type' => 'Adjustment',
                    'qty_in' => $qtyIn,
                    'qty_out' => $qtyOut,
                    'notes' => $adjustment2->reason,
                    'created_at' => $adjustment2->adjusted_at,
                ]);
            }
        }

        // =====================
        // 6. CREATE FINAL STOCK RECEIPT (Restocking before closing)
        // =====================
        
        $receipt3 = Receipt::create([
            'reference' => 'RCP-2025-003',
            'supplier' => 'PT Bahan Baku Indonesia',
            'received_at' => now()->subHours(2),
        ]);

        $receiptItems3 = [
            ['sku' => 'BNBU-001', 'qty' => 50],  // Bawang Bombay
            ['sku' => 'BNBU-002', 'qty' => 50],  // Bawang Merah
            ['sku' => 'BNBU-015', 'qty' => 60],  // Kuning Telur
        ];

        foreach ($receiptItems3 as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->increment('quantity', $item['qty']);
                StockMovement::create([
                    'product_id' => $product->id,
                    'movement_type' => 'receipt',
                    'reference_id' => $receipt3->id,
                    'reference_type' => 'Receipt',
                    'qty_in' => $item['qty'],
                    'qty_out' => 0,
                    'notes' => "Received from {$receipt3->supplier}",
                    'created_at' => $receipt3->received_at,
                ]);
            }
        }
    }
}
