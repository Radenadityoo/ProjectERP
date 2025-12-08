<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceDashboardController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Invoice Dashboard',
            'showViewSwitch' => false,
        ];

        // Get real invoice statistics
        $total_invoices = Invoice::count();
        $total_outstanding = Invoice::where('status', '!=', 'paid')
            ->select(DB::raw('COALESCE(SUM(total - COALESCE(amount_paid, 0)), 0) as outstanding'))
            ->value('outstanding') ?? 0;
        $overdue_invoices = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', '<', now())
            ->count();
        $paid_this_month = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount') ?? 0;

        $stats = [
            'total_invoices' => $total_invoices,
            'total_outstanding' => currency($total_outstanding, 'IDR'),
            'overdue_invoices' => $overdue_invoices,
            'paid_this_month' => currency($paid_this_month, 'IDR'),
        ];

        // Get recent invoices from database
        $recent_invoices = Invoice::with('customer:id,name')
            ->orderByDesc('invoice_date')
            ->take(5)
            ->get()
            ->map(function($invoice) {
                return [
                    'number' => $invoice->number,
                    'customer' => $invoice->customer?->name ?? 'N/A',
                    'invoice_date' => optional($invoice->invoice_date)->format('Y-m-d'),
                    'due_date' => optional($invoice->due_date)->format('Y-m-d'),
                    'status' => ucfirst($invoice->status),
                    'total' => currency($invoice->total, 'IDR'),
                ];
            })
            ->toArray();

        return view('invoicing.dashboard', compact('commandbar', 'stats', 'recent_invoices'));
    }

    /**
     * Return invoice trends data for Chart.js
     */
    public function trendsJson()
    {
        $months = collect(range(5, 0))->map(fn($i) => now()->subMonths($i)->format('Y-m'));

        $invoices = Invoice::selectRaw('DATE_FORMAT(invoice_date, "%Y-%m") as ym, SUM(total) as total')
            ->where('invoice_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $data = $months->map(fn($m) => (float)($invoices[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
