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

        $stats = [
            'total_orders' => 42,
            'total_revenue' => 'Rp 420,000,000',
            'waiting_confirmation' => 8,
            'top_customers_count' => 12,
        ];

        $top_customers = [
            ['name' => 'PT Maju Jaya', 'orders' => 15, 'revenue' => 'Rp 85,000,000'],
            ['name' => 'CV Sentosa Makmur', 'orders' => 12, 'revenue' => 'Rp 72,000,000'],
            ['name' => 'UD Berkah Sejahtera', 'orders' => 10, 'revenue' => 'Rp 65,000,000'],
            ['name' => 'Toko Elektronik Jaya', 'orders' => 8, 'revenue' => 'Rp 48,000,000'],
            ['name' => 'PT Global Trading', 'orders' => 6, 'revenue' => 'Rp 38,000,000'],
        ];

        $trend_data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            'values' => [28, 32, 35, 40, 38, 42],
        ];

        return view('sales.dashboard', compact('commandbar', 'stats', 'top_customers', 'trend_data'));
    }
}
