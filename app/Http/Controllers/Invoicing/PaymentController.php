<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Payments',
            'showViewSwitch' => false,
        ];

        $payments = [
            [
                'id' => 1,
                'payment_number' => 'PMT/2025/0015',
                'customer' => 'PT Maju Jaya',
                'invoice_ref' => 'INV/2025/0008',
                'payment_date' => '2025-01-11',
                'amount' => 'Rp 52,000,000',
                'payment_method' => 'Bank Transfer',
                'status' => 'Posted',
            ],
            [
                'id' => 2,
                'payment_number' => 'PMT/2025/0014',
                'customer' => 'UD Berkah Sejahtera',
                'invoice_ref' => 'INV/2025/0010',
                'payment_date' => '2025-01-15',
                'amount' => 'Rp 35,000,000',
                'payment_method' => 'Bank Transfer',
                'status' => 'Posted',
            ],
            [
                'id' => 3,
                'payment_number' => 'PMT/2025/0013',
                'customer' => 'CV Sentosa Makmur',
                'invoice_ref' => 'INV/2025/0006',
                'payment_date' => '2025-01-12',
                'amount' => 'Rp 18,500,000',
                'payment_method' => 'Cash',
                'status' => 'Posted',
            ],
            [
                'id' => 4,
                'payment_number' => 'PMT/2025/0012',
                'customer' => 'Toko Elektronik Jaya',
                'invoice_ref' => 'INV/2025/0005',
                'payment_date' => '2025-01-08',
                'amount' => 'Rp 12,000,000',
                'payment_method' => 'Bank Transfer',
                'status' => 'Posted',
            ],
        ];

        return view('invoicing.payments.index', compact('commandbar', 'payments'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Register Payment',
            'showViewSwitch' => false,
        ];

        $customers = [
            ['id' => 1, 'name' => 'PT Maju Jaya'],
            ['id' => 2, 'name' => 'CV Sentosa Makmur'],
            ['id' => 3, 'name' => 'UD Berkah Sejahtera'],
            ['id' => 4, 'name' => 'Toko Elektronik Jaya'],
            ['id' => 5, 'name' => 'PT Global Trading'],
        ];

        $invoices = [
            ['id' => 1, 'number' => 'INV/2025/0012', 'customer_id' => 1, 'amount_due' => 42000000],
            ['id' => 2, 'number' => 'INV/2025/0011', 'customer_id' => 2, 'amount_due' => 28500000],
            ['id' => 3, 'number' => 'INV/2025/0009', 'customer_id' => 4, 'amount_due' => 18000000],
        ];

        $journals = [
            ['id' => 1, 'name' => 'Bank - BCA'],
            ['id' => 2, 'name' => 'Bank - Mandiri'],
            ['id' => 3, 'name' => 'Cash'],
        ];

        return view('invoicing.payments.create', compact('commandbar', 'customers', 'invoices', 'journals'));
    }
}
