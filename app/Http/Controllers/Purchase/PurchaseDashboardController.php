<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;

class PurchaseDashboardController extends Controller
{
    public function index()
    {
        $total_pos = \App\Models\PurchaseOrder::whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->count();

        $total_purchased = \App\Models\PurchaseOrder::whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->sum('total');

        $pending_approval = \App\Models\PurchaseOrder::whereIn('status', ['Draft', 'Waiting'])
            ->count();

        $top_vendors = \App\Models\Vendor::withSum('purchaseOrders', 'total')
            ->orderByDesc('purchase_orders_sum_total')
            ->take(3)
            ->get()
            ->map(fn($v) => [
                'name' => $v->name,
                'total' => $v->purchase_orders_sum_total ?? 0,
            ])
            ->toArray();

        $stats = [
            'total_pos' => $total_pos,
            'total_purchased' => $total_purchased ?? 0,
            'pending_approval' => $pending_approval,
        ];

        return view('purchase.dashboard', compact('stats', 'top_vendors'));
    }

    /**
     * Return purchase trends data for Chart.js
     */
    public function trendsJson()
    {
        // Get last 6 months
        $months = collect(range(0, 5))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $orders = \App\Models\PurchaseOrder::selectRaw('DATE_FORMAT(order_date, "%Y-%m") as ym, SUM(total) as total')
            ->where('order_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months;
        $data = $months->map(fn($m) => (float)($orders[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}

