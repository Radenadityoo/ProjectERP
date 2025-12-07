<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /** Display a listing of the products. */
    public function index()
    {
        $products = Product::all();
        return view('components.products.index', ['products' => $products]);
    }

    /** Show the form for creating a new product. */
    public function create()
    {
        // Redirect to admin product creation if user is admin
        return redirect()->route('admin.products.create');
    }

    /** Store a newly created product in storage. */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
        ]);

        Product::create($data);

        return redirect()->route('products.index');
    }

    /** Display the specified product. */
    public function show(Product $product)
    {
        return abort(404);
    }

    /** Show the form for editing the specified product. */
    public function edit(Product $product)
    {
        // Redirect to admin inventory product edit
        return redirect()->route('admin.inventory.products.edit', $product);
    }

    /** Update the specified product in storage. */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric',
        ]);

        $product->update($data);

        return redirect()->route('products.index');
    }

    /** Remove the specified product from storage. */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}

