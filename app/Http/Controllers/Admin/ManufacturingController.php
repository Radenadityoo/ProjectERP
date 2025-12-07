<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Manufacturing;
use Illuminate\Support\Str;

class ManufacturingController extends Controller
{
    public function index(Request $request)
    {
        $items = Manufacturing::with('product')->orderBy('id', 'desc')->paginate(20);

        $commandbar = [
            'title' => 'Manufacturing Orders',
            'count' => Manufacturing::count(),
            'createUrl' => route('admin.manufacturing.create'),
        ];

        return view('admin.manufacturing.index', compact('items', 'commandbar'));
    }

    public function create()
    {
        // compute next reference like WH/MO/0001
        $last = Manufacturing::orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($last && preg_match('/(\d{1,})$/', $last->reference, $m)) {
            $nextNumber = intval($m[1]) + 1;
        }
        $reference = sprintf('WH/MO/%04d', $nextNumber);

        $commandbar = [
            'title' => 'Manufacturing Order',
            'count' => Product::count(),
            'createUrl' => null,
        ];

        $products = Product::orderBy('name')->get();
        return view('admin.manufacturing.create')->with(['commandbar' => $commandbar, 'reference' => $reference, 'products' => $products]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reference' => 'required|string|unique:manufacturings,reference',
            'product_id' => 'nullable|integer',
            'quantity' => 'nullable|numeric',
            'deadline' => 'nullable|date',
        ]);

        $data['status'] = 'draft';

        $mo = Manufacturing::create($data);

        return redirect()->route('admin.manufacturing.index')->with('success', 'Manufacturing Order created: '.$mo->reference);
    }

    public function show(Manufacturing $manufacturing)
    {
        $manufacturing->load('product');
        return view('admin.manufacturing.show', ['mo' => $manufacturing]);
    }

    public function destroy(Manufacturing $manufacturing)
    {
        $manufacturing->delete();
        return redirect()->route('admin.manufacturing.index')->with('success', 'Manufacturing Order deleted.');
    }

    public function updateStatus(Request $request, Manufacturing $manufacturing)
    {
        $request->validate([
            'status' => 'required|in:draft,confirmed,done',
        ]);

        $manufacturing->update(['status' => $request->status]);

        return redirect()->route('admin.manufacturing.show', $manufacturing)->with('success', 'Status updated to ' . ucfirst($request->status));
    }
}
