<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transfer;
use App\Models\StockMovement;

class TransferController extends Controller
{
    public function index()
    {
        $items = Transfer::orderBy('created_at','desc')->paginate(20);
        
        $commandbar = [
            'title' => 'Transfers',
            'count' => $items->total(),
            'showViewSwitch' => false,
        ];

        return view('admin.inventory.transfers.index', compact('items','commandbar'));
    }

    public function create()
    {
        return view('admin.inventory.transfers.form', ['transfer' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string|max:64',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'transferred_at' => 'nullable|date',
            'items' => 'nullable|array',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.qty' => 'required|numeric',
        ]);

        $transfer = Transfer::create([
            'reference' => $data['reference'],
            'from_location' => $data['from_location'],
            'to_location' => $data['to_location'],
            'transferred_at' => $data['transferred_at'] ?? now(),
        ]);

        foreach ($data['items'] ?? [] as $row) {
            StockMovement::create([
                'product_id' => $row['product_id'],
                'type' => 'transfer',
                'reference' => $transfer->reference,
                'quantity' => $row['qty'],
                'source' => $transfer->from_location,
                'destination' => $transfer->to_location,
            ]);
        }

        return redirect()->route('admin.inventory.transfers.index')->with('success','Transfer recorded');
    }
}
