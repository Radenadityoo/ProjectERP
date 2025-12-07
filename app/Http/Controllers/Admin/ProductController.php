<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'nullable|string',
            'track_inventory' => 'nullable|boolean',
            'price' => 'nullable|numeric',
            'cost' => 'nullable|numeric',
            'category' => 'nullable|string',
            'reference' => 'nullable|string',
            'barcode' => 'nullable|string',
            'company' => 'nullable|string',
            'internal_notes' => 'nullable|string',
        ]);

        $data['track_inventory'] = $request->has('track_inventory');

        Product::create($data);

        return redirect()->route('admin.dashboard')->with('status', 'Product created');
    }
}
