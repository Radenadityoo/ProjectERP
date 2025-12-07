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
}
