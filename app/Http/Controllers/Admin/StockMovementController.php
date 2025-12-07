<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StockMovement;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with('product');
        if ($request->filled('q')) {
            $query->where('reference','like','%'.$request->q.'%');
        }

        $items = $query->orderBy('created_at','desc')->paginate(25)->withQueryString();
        
        $commandbar = [
            'title' => 'Stock Movements',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.movements.index', compact('items','commandbar'));
    }
}
