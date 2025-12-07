<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class InventoryProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        if ($request->filled('q')) {
            $query->where('name','like','%'.$request->q.'%')->orWhere('sku','like','%'.$request->q.'%');
        }
        $items = $query->orderBy('name')->paginate(20)->withQueryString();
        
        $commandbar = [
            'title' => 'Products',
            'count' => $items->total(),
            'showViewSwitch' => true,
            'viewStorageKey' => 'productViewMode'
        ];
        
        return view('admin.inventory.products.index', compact('items', 'commandbar'));
    }

    public function create()
    {
        return view('admin.inventory.products.form', ['product' => null]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:64',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
        ]);

        Product::create($data);
        return redirect()->route('admin.inventory.products.index')->with('success','Product created');
    }

    public function edit(Product $product)
    {
        return view('admin.inventory.products.form', ['product' => $product]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:64',
            'price' => 'nullable|numeric',
            'quantity' => 'nullable|numeric',
        ]);

        $product->update($data);
        return redirect()->route('admin.inventory.products.index')->with('success','Product updated');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.inventory.products.index')->with('success','Product deleted');
    }
}
