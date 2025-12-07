<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function index()
    {
        $items = Delivery::orderBy('created_at','desc')->paginate(20);
        
        $commandbar = [
            'title' => 'Deliveries',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.deliveries.index', compact('items','commandbar'));
    }

    public function create()
    {
        return view('admin.inventory.deliveries.form', ['delivery' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string|max:64',
            'customer' => 'nullable|string|max:255',
            'shipped_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric',
        ]);

        DB::transaction(function() use ($data) {
            $delivery = Delivery::create([
                'reference' => $data['reference'],
                'customer' => $data['customer'] ?? null,
                'shipped_at' => $data['shipped_at'] ?? now(),
            ]);

            foreach ($data['items'] ?? [] as $row) {
                StockMovement::create([
                    'product_id' => $row['product_id'],
                    'type' => 'out',
                    'reference' => $delivery->reference,
                    'quantity' => $row['qty'],
                    'source' => 'warehouse',
                    'destination' => $delivery->customer,
                ]);

                // decrement product quantity
                $product = Product::where('id', $row['product_id'])->lockForUpdate()->first();
                if ($product) {
                    $product->quantity = max(0, ($product->quantity ?? 0) - floatval($row['qty']));
                    $product->save();
                }
            }
        });

        return redirect()->route('admin.inventory.deliveries.index')->with('success','Delivery created');
    }
}
