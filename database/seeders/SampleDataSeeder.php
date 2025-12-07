<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\BomHeader;
use App\Models\BomComponent;
use App\Models\Manufacturing;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
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

        // Link finished products into sales orders and invoices
        $customers = Customer::whereIn('name', [
            'PT Nusantara Jaya',
            'CV Cahaya Mandiri',
            'UD Berkah Sejahtera',
            'Toko Sejahtera Elektronik',
        ])->get()->keyBy('name');

        $productsByRef = Product::whereIn('reference', ['MKR-01', 'MKR-02', 'MKR-03'])->get()->keyBy('reference');

        $salesOrders = [
            [
                'so_number' => 'SO-2025-0101',
                'customer_name' => 'PT Nusantara Jaya',
                'order_date' => '2025-12-04',
                'delivery_date' => '2025-12-10',
                'status' => 'confirmed',
                'notes' => 'From MO WH/MO/0101',
                'lines' => [
                    ['ref' => 'MKR-01', 'qty' => 20, 'price' => 35000, 'tax' => 11],
                    ['ref' => 'MKR-02', 'qty' => 15, 'price' => 20000, 'tax' => 11],
                ],
            ],
            [
                'so_number' => 'SO-2025-0102',
                'customer_name' => 'CV Cahaya Mandiri',
                'order_date' => '2025-12-05',
                'delivery_date' => '2025-12-12',
                'status' => 'confirmed',
                'notes' => 'From MO WH/MO/0102',
                'lines' => [
                    ['ref' => 'MKR-03', 'qty' => 25, 'price' => 40000, 'tax' => 11],
                    ['ref' => 'MKR-02', 'qty' => 10, 'price' => 20000, 'tax' => 11],
                ],
            ],
            [
                'so_number' => 'SO-2025-0103',
                'customer_name' => 'UD Berkah Sejahtera',
                'order_date' => '2025-12-06',
                'delivery_date' => '2025-12-15',
                'status' => 'draft',
                'notes' => 'Pending confirmation',
                'lines' => [
                    ['ref' => 'MKR-01', 'qty' => 10, 'price' => 35000, 'tax' => 11],
                ],
            ],
        ];

        foreach ($salesOrders as $row) {
            $customer = $customers[$row['customer_name']] ?? Customer::first();
            if (! $customer) {
                continue;
            }

            $order = SalesOrder::updateOrCreate(
                ['so_number' => $row['so_number']],
                [
                    'customer_id' => $customer->id,
                    'order_date' => $row['order_date'],
                    'delivery_date' => $row['delivery_date'],
                    'status' => $row['status'],
                    'notes' => $row['notes'] ?? null,
                ]
            );

            $order->items()->delete();
            $subtotal = 0;
            $taxTotal = 0;

            foreach ($row['lines'] as $line) {
                $product = $productsByRef[$line['ref']] ?? null;
                $lineSubtotal = $line['qty'] * $line['price'];
                $lineTax = ($line['tax'] / 100) * $lineSubtotal;

                SalesOrderItem::create([
                    'sales_order_id' => $order->id,
                    'product_id' => $product?->id,
                    'description' => $product?->name,
                    'quantity' => $line['qty'],
                    'unit_price' => $line['price'],
                    'tax_rate' => $line['tax'],
                    'subtotal' => $lineSubtotal,
                ]);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
            }

            $order->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total' => $subtotal + $taxTotal,
            ]);
        }

        // Create invoices linked to sales orders
        $salesOrdersIndexed = SalesOrder::with(['items', 'customer'])->whereIn('so_number', [
            'SO-2025-0101', 'SO-2025-0102', 'SO-2025-0103',
        ])->get()->keyBy('so_number');

        $invoices = [
            [
                'number' => 'INV/2025/0101',
                'so_number' => 'SO-2025-0101',
                'invoice_date' => '2025-12-06',
                'due_date' => '2025-12-16',
                'status' => 'posted',
            ],
            [
                'number' => 'INV/2025/0102',
                'so_number' => 'SO-2025-0102',
                'invoice_date' => '2025-12-07',
                'due_date' => '2025-12-17',
                'status' => 'posted',
            ],
            [
                'number' => 'INV/2025/0103',
                'so_number' => 'SO-2025-0103',
                'invoice_date' => '2025-12-08',
                'due_date' => '2025-12-18',
                'status' => 'draft',
            ],
        ];

        foreach ($invoices as $row) {
            $order = $salesOrdersIndexed[$row['so_number']] ?? null;
            if (! $order) {
                continue;
            }

            $invoice = Invoice::updateOrCreate(
                ['number' => $row['number']],
                [
                    'sales_order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'invoice_date' => $row['invoice_date'],
                    'due_date' => $row['due_date'],
                    'status' => $row['status'],
                    'reference' => $order->so_number,
                ]
            );

            $invoice->items()->delete();

            $subtotal = 0;
            $taxTotal = 0;

            foreach ($order->items as $item) {
                $lineSubtotal = $item->quantity * $item->unit_price;
                $lineTax = ($item->tax_rate / 100) * $lineSubtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'tax_rate' => $item->tax_rate,
                    'subtotal' => $lineSubtotal,
                ]);

                $subtotal += $lineSubtotal;
                $taxTotal += $lineTax;
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'tax_amount' => $taxTotal,
                'total' => $subtotal + $taxTotal,
            ]);
        }
    }
}
