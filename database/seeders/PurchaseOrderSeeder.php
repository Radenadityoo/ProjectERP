<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $vendor1 = Vendor::where('name', 'PT Mitra Logistik')->first();
        $vendor2 = Vendor::where('name', 'CV Bumi Makmur')->first();
        $vendor3 = Vendor::where('name', 'PT Cahaya Elektronik')->first();
        $vendor4 = Vendor::where('name', 'PT Anugerah Mesin')->first();

        $terigu = Product::where('reference', 'BNBU-009')->first();
        $bawangMerah = Product::where('reference', 'BNBU-002')->first();
        $bawangPutih = Product::where('reference', 'BNBU-003')->first();
        $tapioka = Product::where('reference', 'BNBU-020')->first();

        // PO 1
        $po1 = PurchaseOrder::create([
            'po_number' => 'PO-2025-0101',
            'vendor_id' => $vendor1?->id,
            'order_date' => '2025-12-03',
            'expected_arrival' => '2025-12-10',
            'currency' => 'IDR',
            'status' => 'draft',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po1->id,
            'product_id' => $terigu?->id,
            'description' => 'Tepung terigu protein tinggi',
            'quantity' => 100,
            'unit_price' => 12000,
            'tax_rate' => 'PPN 11%',
            'subtotal' => 1200000,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po1->id,
            'product_id' => $bawangMerah?->id,
            'description' => 'Bawang merah Brebes grade A',
            'quantity' => 50,
            'unit_price' => 45000,
            'tax_rate' => 'PPN 11%',
            'subtotal' => 2250000,
        ]);

        $po1->recalculateTotals();
        $po1->update(['tax_amount' => $po1->subtotal * 0.11]);
        $po1->recalculateTotals();

        // PO 2
        $po2 = PurchaseOrder::create([
            'po_number' => 'PO-2025-0100',
            'vendor_id' => $vendor2?->id,
            'order_date' => '2025-12-01',
            'expected_arrival' => '2025-12-08',
            'currency' => 'IDR',
            'status' => 'waiting',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po2->id,
            'product_id' => $bawangPutih?->id,
            'description' => 'Bawang putih lokal',
            'quantity' => 80,
            'unit_price' => 40000,
            'tax_rate' => 'PPN 11%',
            'subtotal' => 3200000,
        ]);

        $po2->recalculateTotals();
        $po2->update(['tax_amount' => $po2->subtotal * 0.11]);
        $po2->recalculateTotals();

        // PO 3
        $po3 = PurchaseOrder::create([
            'po_number' => 'PO-2025-0099',
            'vendor_id' => $vendor3?->id,
            'order_date' => '2025-11-28',
            'expected_arrival' => '2025-12-06',
            'currency' => 'IDR',
            'status' => 'purchase',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po3->id,
            'product_id' => $tapioka?->id,
            'description' => 'Tepung tapioka premium',
            'quantity' => 150,
            'unit_price' => 15000,
            'tax_rate' => 'PPN 11%',
            'subtotal' => 2250000,
        ]);

        $po3->recalculateTotals();
        $po3->update(['tax_amount' => $po3->subtotal * 0.11]);
        $po3->recalculateTotals();

        // PO 4
        $po4 = PurchaseOrder::create([
            'po_number' => 'PO-2025-0098',
            'vendor_id' => $vendor4?->id,
            'order_date' => '2025-11-25',
            'expected_arrival' => '2025-12-02',
            'currency' => 'IDR',
            'status' => 'received',
            'subtotal' => 0,
            'tax_amount' => 0,
            'total' => 0,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $po4->id,
            'product_id' => $terigu?->id,
            'description' => 'Tepung terigu serbaguna',
            'quantity' => 200,
            'unit_price' => 11000,
            'tax_rate' => 'PPN 11%',
            'subtotal' => 2200000,
        ]);

        $po4->recalculateTotals();
        $po4->update(['tax_amount' => $po4->subtotal * 0.11]);
        $po4->recalculateTotals();
    }
}
