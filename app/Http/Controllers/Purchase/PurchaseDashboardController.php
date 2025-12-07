<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;

class PurchaseDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pos' => 24,
            'total_purchased' => 182400,
            'pending_approval' => 6,
        ];

        $top_vendors = [
            ['name' => 'Acme Supplies', 'total' => 52300],
            ['name' => 'Northwind Traders', 'total' => 38600],
            ['name' => 'Globex', 'total' => 28500],
        ];

        $trend_data = [12000, 18000, 15000, 22000, 26000, 24000];

        return view('purchase.dashboard', compact('stats', 'top_vendors', 'trend_data'));
    }
}
