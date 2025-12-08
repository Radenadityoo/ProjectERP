<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\SalesOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesDashboardController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Sales Dashboard',
            'showViewSwitch' => false,
        ];

        // Get real sales order data
        $total_orders = SalesOrder::count();
        $total_revenue = SalesOrder::sum('total') ?? 0;
        $waiting_confirmation = SalesOrder::whereIn('status', ['draft', 'confirmed'])->count();
        
        // Get top customers by total order value
        $top_customers_data = SalesOrder::select('customer_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(total) as total_revenue'))
            ->with('customer')
            ->groupBy('customer_id')
            ->orderByDesc('total_revenue')
            ->take(5)
            ->get();

        $top_customers_count = Customer::count();

        $stats = [
            'total_orders' => $total_orders,
            'total_revenue' => currency($total_revenue, 'IDR'),
            'waiting_confirmation' => $waiting_confirmation,
            'top_customers_count' => $top_customers_count,
        ];

        $top_customers = $top_customers_data->map(fn($item) => [
            'name' => $item->customer->name ?? 'Unknown',
            'orders' => $item->order_count,
            'revenue' => currency($item->total_revenue ?? 0, 'IDR'),
        ])->toArray();

        // Get last 6 months trend data
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i);
        });

        $trend_labels = $months->map(fn($date) => $date->format('M'))->toArray();
        
        $monthlyRevenue = SalesOrder::selectRaw('DATE_FORMAT(order_date, "%Y-%m") as ym, SUM(total) as total')
            ->where('order_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $trend_values = $months->map(function($date) use ($monthlyRevenue) {
            $key = $date->format('Y-m');
            return (float)($monthlyRevenue[$key] ?? 0) / 1000; // Convert to thousands for readability
        })->toArray();

        $trend_data = [
            'labels' => $trend_labels,
            'values' => $trend_values,
        ];

        return view('sales.dashboard', compact('commandbar', 'stats', 'top_customers', 'trend_data'));
    }

    /**
     * Return sales trends data for Chart.js
     */
    public function trendsJson()
    {
        // Get last 6 months of sales order data
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->format('Y-m');
        })->values();

        $orders = SalesOrder::selectRaw('DATE_FORMAT(order_date, "%Y-%m") as ym, SUM(total) as total')
            ->where('order_date', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('ym')
            ->orderBy('ym')
            ->pluck('total', 'ym');

        $labels = $months->map(fn($m) => \Carbon\Carbon::parse($m . '-01')->format('M Y'));
        $data = $months->map(fn($m) => (float)($orders[$m] ?? 0));

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
