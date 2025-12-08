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
                    'type' => 'receipt',
                    'reference' => $receipt1->reference,
                    'quantity' => $item['qty'],
                    'source' => $receipt1->supplier,
                    'notes' => "Received from {$receipt1->supplier}",
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
                    'type' => 'receipt',
                    'reference' => $receipt2->reference,
                    'quantity' => $item['qty'],
                    'source' => $receipt2->supplier,
                    'notes' => "Received from {$receipt2->supplier}",
                ]);
            }
        }

        // =====================
        // 2. MANUFACTURING PRODUCTION (Simulated)
        // =====================
        
        $adjustment1 = Adjustment::create([
            'reference' => 'ADJ-2025-001',
            'reason' => 'Manufacturing Production - Kroket Kentang Batch 1',
            'adjusted_at' => now()->subDays(5),
        ]);

        // Components used for Kroket Kentang
        $kroketComponents = [
            ['sku' => 'BNBU-002', 'qty' => -50],   // Bawang Merah
            ['sku' => 'BNBU-003', 'qty' => -33],   // Bawang Putih
            ['sku' => 'BNBU-009', 'qty' => -104],  // Tepung Terigu
        ];

        foreach ($kroketComponents as $item) {
            $product = Product::where('reference', $item['sku'])->first();
            if ($product) {
                $product->decrement('quantity', abs($item['qty']));
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'reference' => $adjustment1->reference,
                    'quantity' => $item['qty'],
                    'notes' => "Components used for Kroket Kentang production",
                ]);
            }
        }

        // Finished product produced
        $kroketProduct = Product::where('reference', 'MKR-01')->first();
        if ($kroketProduct) {
            $kroketProduct->increment('quantity', 40);
            StockMovement::create([
                'product_id' => $kroketProduct->id,
                'type' => 'adjustment',
                'reference' => $adjustment1->reference,
                'quantity' => 40,
                'destination' => 'Main Warehouse',
                'notes' => "Kroket Kentang produced - Batch 1",
            ]);
        }

        // =====================
        // 3. DELIVERIES (Sales/Outbound)
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
                    'type' => 'delivery',
                    'reference' => $delivery1->reference,
                    'quantity' => -$item['qty'],
                    'destination' => $delivery1->customer,
                    'notes' => "Delivered to {$delivery1->customer}",
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
                    'type' => 'delivery',
                    'reference' => $delivery2->reference,
                    'quantity' => -$item['qty'],
                    'destination' => $delivery2->customer,
                    'notes' => "Delivered to {$delivery2->customer}",
                ]);
            }
        }

        // =====================
        // 4. TRANSFERS (Between locations)
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
                    'type' => 'transfer',
                    'reference' => $transfer1->reference,
                    'quantity' => 0,
                    'source' => $transfer1->from_location,
                    'destination' => $transfer1->to_location,
                    'notes' => "Transferred from {$transfer1->from_location} to {$transfer1->to_location}",
                ]);
            }
        }

        // =====================
        // 5. ADJUSTMENTS (Discrepancies/Damage)
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
                } else {
                    $product->decrement('quantity', abs($item['qty']));
                }

                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'reference' => $adjustment2->reference,
                    'quantity' => $item['qty'],
                    'notes' => $adjustment2->reason,
                ]);
            }
        }

        // =====================
        // 6. FINAL STOCK RECEIPT (Restocking)
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
                    'type' => 'receipt',
                    'reference' => $receipt3->reference,
                    'quantity' => $item['qty'],
                    'source' => $receipt3->supplier,
                    'notes' => "Received from {$receipt3->supplier}",
                ]);
            }
        }
    }
}
