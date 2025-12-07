<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Sales Orders',
            'showViewSwitch' => false,
        ];

        $orders = [
            [
                'id' => 1,
                'so_number' => 'SO-2025-001',
                'customer' => 'PT Maju Jaya',
                'order_date' => '2025-01-15',
                'delivery_date' => '2025-01-25',
                'status' => 'confirmed',
                'total' => 'Rp 25,000,000',
            ],
            [
                'id' => 2,
                'so_number' => 'SO-2025-002',
                'customer' => 'CV Sentosa Makmur',
                'order_date' => '2025-01-18',
                'delivery_date' => '2025-01-28',
                'status' => 'draft',
                'total' => 'Rp 18,500,000',
            ],
            [
                'id' => 3,
                'so_number' => 'SO-2025-003',
                'customer' => 'UD Berkah Sejahtera',
                'order_date' => '2025-01-20',
                'delivery_date' => '2025-02-05',
                'status' => 'confirmed',
                'total' => 'Rp 32,000,000',
            ],
            [
                'id' => 4,
                'so_number' => 'SO-2025-004',
                'customer' => 'Toko Elektronik Jaya',
                'order_date' => '2025-01-22',
                'delivery_date' => '2025-02-10',
                'status' => 'delivered',
                'total' => 'Rp 15,000,000',
            ],
            [
                'id' => 5,
                'so_number' => 'SO-2025-005',
                'customer' => 'PT Global Trading',
                'order_date' => '2025-01-25',
                'delivery_date' => '2025-02-15',
                'status' => 'cancelled',
                'total' => 'Rp 22,000,000',
            ],
        ];

        return view('sales.orders.index', compact('commandbar', 'orders'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Sales Order',
            'showViewSwitch' => false,
        ];

        $customers = [
            ['id' => 1, 'name' => 'PT Maju Jaya'],
            ['id' => 2, 'name' => 'CV Sentosa Makmur'],
            ['id' => 3, 'name' => 'UD Berkah Sejahtera'],
            ['id' => 4, 'name' => 'Toko Elektronik Jaya'],
            ['id' => 5, 'name' => 'PT Global Trading'],
        ];

        $products = [
            ['id' => 1, 'name' => 'Laptop Dell Latitude', 'price' => 12000000],
            ['id' => 2, 'name' => 'Monitor LG 24"', 'price' => 2500000],
            ['id' => 3, 'name' => 'Keyboard Mechanical', 'price' => 850000],
            ['id' => 4, 'name' => 'Mouse Wireless', 'price' => 350000],
            ['id' => 5, 'name' => 'Headset Gaming', 'price' => 1200000],
        ];

        return view('sales.orders.create', compact('commandbar', 'customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
        ]);

        $id = now()->timestamp;

        return redirect()->route('sales.orders.index')->with('success', 'Sales order saved (demo) ID '.$id);
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Sales Order',
            'showViewSwitch' => false,
        ];

        $order = [
            'id' => $id,
            'so_number' => 'SO-2025-001',
            'customer_id' => 1,
            'order_date' => '2025-01-15',
            'delivery_date' => '2025-01-25',
            'payment_terms' => '30 days',
            'pricelist' => 'Standard',
            'status' => 'confirmed',
            'lines' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Laptop Dell Latitude',
                    'description' => 'High-performance business laptop',
                    'quantity' => 5,
                    'unit_price' => 12000000,
                    'tax' => 11,
                ],
                [
                    'product_id' => 2,
                    'product_name' => 'Monitor LG 24"',
                    'description' => 'Full HD IPS display',
                    'quantity' => 10,
                    'unit_price' => 2500000,
                    'tax' => 11,
                ],
            ],
        ];

        return view('sales.orders.edit', compact('commandbar', 'order', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
        ]);

        return redirect()->route('sales.orders.index')->with('success', 'Sales order updated (demo) ID '.$id);
    }
}
