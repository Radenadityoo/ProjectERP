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

        // Basic stats derived from available customer data (acts as invoices demo)
        $total_invoices = \App\Models\Customer::count();
        $total_outstanding = \App\Models\Customer::sum('total_spend');
        $overdue_invoices = \App\Models\Customer::where('tags', 'like', '%overdue%')->count();
        $paid_this_month = \App\Models\Customer::whereMonth('updated_at', now()->month)->sum('total_spend');

        $stats = [
            'total_invoices' => $total_invoices,
            'total_outstanding' => currency($total_outstanding, 'IDR'),
            'overdue_invoices' => $overdue_invoices,
            'paid_this_month' => currency($paid_this_month, 'IDR'),
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

        return view('invoicing.dashboard', compact('commandbar', 'stats', 'recent_invoices'));
    }

    /**
     * Return invoice trends data for Chart.js (using customer total_spend as demo invoices)
     */
    public function trendsJson()
    {
        $months = collect(range(0, 5))->map(fn($i) => now()->subMonths($i)->format('Y-m'))->reverse()->values();

        $invoices = \App\Models\Customer::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, SUM(total_spend) as total')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months;
        $data = $months->map(fn($m) => (float)($invoices[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
