<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Invoices',
            'showViewSwitch' => false,
        ];

        $invoices = [
            [
                'id' => 1,
                'number' => 'INV/2025/0012',
                'customer' => 'PT Maju Jaya',
                'invoice_date' => '2025-01-10',
                'due_date' => '2025-01-20',
                'status' => 'Posted',
                'total' => 'Rp 42,000,000',
            ],
            [
                'id' => 2,
                'number' => 'INV/2025/0011',
                'customer' => 'CV Sentosa Makmur',
                'invoice_date' => '2025-01-08',
                'due_date' => '2025-01-18',
                'status' => 'Posted',
                'total' => 'Rp 28,500,000',
            ],
            [
                'id' => 3,
                'number' => 'INV/2025/0010',
                'customer' => 'UD Berkah Sejahtera',
                'invoice_date' => '2025-01-05',
                'due_date' => '2025-01-15',
                'status' => 'Paid',
                'total' => 'Rp 35,000,000',
            ],
            [
                'id' => 4,
                'number' => 'INV/2025/0009',
                'customer' => 'Toko Elektronik Jaya',
                'invoice_date' => '2025-01-03',
                'due_date' => '2025-01-13',
                'status' => 'Overdue',
                'total' => 'Rp 18,000,000',
            ],
            [
                'id' => 5,
                'number' => 'INV/2025/0008',
                'customer' => 'PT Global Trading',
                'invoice_date' => '2025-01-01',
                'due_date' => '2025-01-11',
                'status' => 'Paid',
                'total' => 'Rp 52,000,000',
            ],
            [
                'id' => 6,
                'number' => 'INV/2025/0007',
                'customer' => 'PT Maju Jaya',
                'invoice_date' => '2024-12-28',
                'due_date' => '2025-01-07',
                'status' => 'Draft',
                'total' => 'Rp 22,000,000',
            ],
        ];

        return view('invoicing.invoices.index', compact('commandbar', 'invoices'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Invoice',
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

        $journals = [
            ['id' => 1, 'name' => 'Customer Invoices'],
            ['id' => 2, 'name' => 'Credit Notes'],
        ];

        return view('invoicing.invoices.create', compact('commandbar', 'customers', 'products', 'journals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
        ]);

        $id = now()->timestamp;

        return redirect()->route('invoicing.invoices.index')->with('success', 'Invoice saved (demo) ID '.$id);
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Invoice',
            'showViewSwitch' => false,
        ];

        $invoice = [
            'id' => $id,
            'number' => 'INV/2025/0012',
            'customer_id' => 1,
            'invoice_date' => '2025-01-10',
            'due_date' => '2025-01-20',
            'journal_id' => 1,
            'reference' => 'SO-2025-001',
            'status' => 'Posted',
            'lines' => [
                [
                    'product_id' => 1,
                    'product_name' => 'Laptop Dell Latitude',
                    'label' => 'High-performance business laptop',
                    'quantity' => 5,
                    'unit_price' => 12000000,
                    'tax' => 11,
                ],
                [
                    'product_id' => 2,
                    'product_name' => 'Monitor LG 24"',
                    'label' => 'Full HD IPS display',
                    'quantity' => 10,
                    'unit_price' => 2500000,
                    'tax' => 11,
                ],
            ],
        ];

        return view('invoicing.invoices.edit', compact('commandbar', 'invoice', 'customers', 'products', 'journals'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'customer_id' => 'required',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
        ]);

        return redirect()->route('invoicing.invoices.index')->with('success', 'Invoice updated (demo) ID '.$id);
    }
}
