<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Receipt;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index()
    {
        $items = Receipt::orderBy('created_at','desc')->paginate(20);
        
        $commandbar = [
            'title' => 'Receipts',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.receipts.index', compact('items','commandbar'));
    }

    public function create()
    {
        return view('admin.inventory.receipts.form', ['receipt' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string|max:64',
            'supplier' => 'nullable|string|max:255',
            'received_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric',
        ]);

        DB::transaction(function() use ($data) {
            $receipt = Receipt::create([
                'reference' => $data['reference'],
                'supplier' => $data['supplier'] ?? null,
                'received_at' => $data['received_at'] ?? now(),
            ]);

            foreach ($data['items'] ?? [] as $row) {
                StockMovement::create([
                    'product_id' => $row['product_id'],
                    'type' => 'in',
                    'reference' => $receipt->reference,
                    'quantity' => $row['qty'],
                    'source' => $receipt->supplier,
                    'destination' => 'warehouse',
                ]);

                // update product quantity atomically
                $product = Product::where('id', $row['product_id'])->lockForUpdate()->first();
                if ($product) {
                    $product->quantity = ($product->quantity ?? 0) + floatval($row['qty']);
                    $product->save();
                }
            }
        });

        return redirect()->route('admin.inventory.receipts.index')->with('success','Receipt recorded');
    }
}
