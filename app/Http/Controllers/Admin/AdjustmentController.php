<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adjustment;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class AdjustmentController extends Controller
{
    public function index()
    {
        $items = Adjustment::orderBy('created_at','desc')->paginate(20);
        
        $commandbar = [
            'title' => 'Adjustments',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.adjustments.index', compact('items','commandbar'));
    }

    public function create()
    {
        return view('admin.inventory.adjustments.form', ['adjustment' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string|max:64',
            'reason' => 'required|string|max:255',
            'adjusted_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric',
        ]);

        DB::transaction(function() use ($data) {
            $adj = Adjustment::create([
                'reference' => $data['reference'],
                'reason' => $data['reason'],
                'adjusted_at' => $data['adjusted_at'] ?? now(),
            ]);

            foreach ($data['items'] ?? [] as $row) {
                StockMovement::create([
                    'product_id' => $row['product_id'],
                    'type' => 'adjustment',
                    'reference' => $adj->reference,
                    'quantity' => $row['qty'],
                    'source' => null,
                    'destination' => null,
                    'notes' => $data['reason'],
                ]);

                // apply adjustment to product quantity (qty may be negative)
                $product = Product::where('id', $row['product_id'])->lockForUpdate()->first();
                if ($product) {
                    $product->quantity = ($product->quantity ?? 0) + floatval($row['qty']);
                    $product->save();
                }
            }
        });

        return redirect()->route('admin.inventory.adjustments.index')->with('success','Adjustment saved');
    }
}
