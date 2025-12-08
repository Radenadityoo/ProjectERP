<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();
        $weekAhead = Carbon::today()->addDays(7);

        // KPI metrics
        $openSalesOrders = SalesOrder::whereNotIn('status', ['delivered', 'cancelled'])->count();
        $invoicesDueSoon = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', '>=', $today)
            ->whereDate('due_date', '<=', $weekAhead)
            ->count();
        $overdueInvoices = Invoice::where('status', '!=', 'paid')
            ->whereDate('due_date', '<', $today)
            ->count();
        $pendingPayments = Payment::whereIn('status', ['posted', 'pending'])->count();

        $outstandingAmount = Invoice::where('status', '!=', 'paid')
            ->select(DB::raw('COALESCE(SUM(total - COALESCE(amount_paid,0)),0) as outstanding'))
            ->value('outstanding') ?? 0;

        $kpis = [
            [
                'label' => 'Open Sales Orders',
                'value' => $openSalesOrders,
                'delta' => $openSalesOrders > 0 ? '+ active' : 'clear',
                'color' => 'bg-blue-500',
            ],
            [
                'label' => 'Invoices Due (7d)',
                'value' => $invoicesDueSoon,
                'delta' => $invoicesDueSoon > 0 ? 'review soon' : 'none due',
                'color' => 'bg-indigo-500',
            ],
            [
                'label' => 'Overdue Invoices',
                'value' => $overdueInvoices,
                'delta' => $outstandingAmount > 0 ? 'outstanding ' . number_format($outstandingAmount, 0) : 'all clear',
                'color' => 'bg-rose-500',
            ],
            [
                'label' => 'Payments Pending',
                'value' => $pendingPayments,
                'delta' => $pendingPayments > 0 ? 'awaiting approval' : 'none',
                'color' => 'bg-amber-500',
            ],
        ];

        // Revenue vs Purchases (current month)
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;
        $revenueMonth = Invoice::whereYear('invoice_date', $year)->whereMonth('invoice_date', $month)->sum('total');
        $purchaseMonth = PurchaseOrder::whereYear('order_date', $year)->whereMonth('order_date', $month)->sum('total');
        $scale = max($revenueMonth, $purchaseMonth, 1);

        $chartCards = [
            [
                'title' => 'Revenue vs. Purchases (This Month)',
                'series' => [
                    ['label' => 'Revenue', 'value' => (int) round(($revenueMonth / $scale) * 100), 'color' => 'bg-emerald-500'],
                    ['label' => 'Purchases', 'value' => (int) round(($purchaseMonth / $scale) * 100), 'color' => 'bg-blue-500'],
                ],
                'footer' => 'Live based on invoices and purchase orders this month.',
            ],
        ];

        // Fulfillment health from sales orders
        $delivered = SalesOrder::where('status', 'delivered')->count();
        $inTransit = SalesOrder::where('status', 'confirmed')->count();
        $pending = SalesOrder::where('status', 'draft')->count();
        $delayed = SalesOrder::whereNotIn('status', ['delivered', 'cancelled'])
            ->whereDate('delivery_date', '<', $today)
            ->count();

        $fulfillmentTotal = max($delivered + $inTransit + $pending, 1);
        $chartCards[] = [
            'title' => 'Fulfillment Health',
            'series' => [
                ['label' => 'Delivered', 'value' => (int) round(($delivered / $fulfillmentTotal) * 100), 'color' => 'bg-emerald-500'],
                ['label' => 'In Transit', 'value' => (int) round(($inTransit / $fulfillmentTotal) * 100), 'color' => 'bg-blue-500'],
                ['label' => 'Delayed', 'value' => (int) round(($delayed / $fulfillmentTotal) * 100), 'color' => 'bg-rose-500'],
            ],
            'footer' => 'Calculated from sales orders delivery status.',
        ];

        $criticalReports = [
            [
                'label' => 'Overdue Invoices',
                'count' => $overdueInvoices,
                'link' => route('invoicing.invoices.index'),
                'badge' => 'Finance',
            ],
            [
                'label' => 'Invoices Due (7d)',
                'count' => $invoicesDueSoon,
                'link' => route('invoicing.invoices.index'),
                'badge' => 'Billing',
            ],
            [
                'label' => 'Pending Payments',
                'count' => $pendingPayments,
                'link' => route('invoicing.payments.index'),
                'badge' => 'Cash',
            ],
            [
                'label' => 'Open Sales Orders',
                'count' => $openSalesOrders,
                'link' => route('sales.orders.index'),
                'badge' => 'Fulfillment',
            ],
        ];

        return view('dashboard', [
            'kpis' => $kpis,
            'chartCards' => $chartCards,
            'criticalReports' => $criticalReports,
        ]);
    }
}