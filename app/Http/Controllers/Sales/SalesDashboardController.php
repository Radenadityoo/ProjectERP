<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Sales Dashboard',
            'showViewSwitch' => false,
        ];

        // Using available models - Customer represents customer data
        $total_orders = \App\Models\Customer::count() ?? 0;
        $total_revenue = \App\Models\Customer::sum('total_spend') ?? 0;
        $waiting_confirmation = \App\Models\Customer::where('tags', 'like', '%pending%')->count() ?? 0;
        $top_customers_count = $total_orders;

        $stats = [
            'total_orders' => $total_orders,
            'total_revenue' => currency($total_revenue, 'IDR'),
            'waiting_confirmation' => $waiting_confirmation,
            'top_customers_count' => $top_customers_count,
        ];

        $top_customers = \App\Models\Customer::orderByDesc('total_spend')
            ->take(5)
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'orders' => rand(5, 20), // Demo count
                'revenue' => currency($c->total_spend ?? 0, 'IDR'),
            ])
            ->toArray();

        $trend_data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [28, 32, 35, 40, 38, 42],
        ];

        return view('sales.dashboard', compact('commandbar', 'stats', 'top_customers', 'trend_data'));
    }

    /**
     * Return sales trends data for Chart.js
     */
    public function trendsJson()
    {
        // Get last 6 months of data from customers created
        $months = collect(range(0, 5))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->reverse()->values();

        $customers = \App\Models\Customer::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, SUM(total_spend) as total')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months;
        $data = $months->map(fn($m) => (float)($customers[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
