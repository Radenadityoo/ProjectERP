<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;

class InventoryDashboardController extends Controller
{
    public function index()
    {
        $productsCount = Product::count();
        $stockMovementsToday = StockMovement::whereDate('created_at', now()->toDateString())->count();
        $lowStock = Product::where('quantity', '<', 10)->count();

        $commandbar = [
            'title' => 'Inventory Dashboard',
            'count' => $productsCount,
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.dashboard', compact('productsCount','stockMovementsToday','lowStock','commandbar'));
    }

    /**
     * Return stock movements data for Chart.js (last 7 days)
     */
    public function movementsJson()
    {
        $days = collect(range(0, 6))->map(function ($i) {
            return now()->subDays($i)->format('Y-m-d');
        })->reverse()->values();

        $movements = StockMovement::selectRaw('DATE(created_at) as day, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('count', 'day');

        $labels = $days->map(fn($d) => now()->parse($d)->format('D'));
        $data = $days->map(fn($d) => $movements[$d] ?? 0);

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
