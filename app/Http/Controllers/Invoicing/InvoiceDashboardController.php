<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceDashboardController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Invoice Dashboard',
            'showViewSwitch' => false,
        ];

        $stats = [
            'total_invoices' => 128,
            'total_outstanding' => 'Rp 91,000,000',
            'overdue_invoices' => 5,
            'paid_this_month' => 'Rp 125,000,000',
        ];

        $recent_invoices = [
            [
                'number' => 'INV/2025/0012',
                'customer' => 'PT Maju Jaya',
                'invoice_date' => '2025-01-10',
                'due_date' => '2025-01-20',
                'status' => 'Posted',
                'total' => 'Rp 42,000,000',
            ],
            [
                'number' => 'INV/2025/0011',
                'customer' => 'CV Sentosa Makmur',
                'invoice_date' => '2025-01-08',
                'due_date' => '2025-01-18',
                'status' => 'Posted',
                'total' => 'Rp 28,500,000',
            ],
            [
                'number' => 'INV/2025/0010',
                'customer' => 'UD Berkah Sejahtera',
                'invoice_date' => '2025-01-05',
                'due_date' => '2025-01-15',
                'status' => 'Paid',
                'total' => 'Rp 35,000,000',
            ],
            [
                'number' => 'INV/2025/0009',
                'customer' => 'Toko Elektronik Jaya',
                'invoice_date' => '2025-01-03',
                'due_date' => '2025-01-13',
                'status' => 'Overdue',
                'total' => 'Rp 18,000,000',
            ],
            [
                'number' => 'INV/2025/0008',
                'customer' => 'PT Global Trading',
                'invoice_date' => '2025-01-01',
                'due_date' => '2025-01-11',
                'status' => 'Paid',
                'total' => 'Rp 52,000,000',
            ],
        ];

        $trend_data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [85, 92, 88, 95, 90, 91],
        ];

        return view('invoicing.dashboard', compact('commandbar', 'stats', 'recent_invoices', 'trend_data'));
    }
}
