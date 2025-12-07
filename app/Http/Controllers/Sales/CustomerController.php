<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Customers',
            'showViewSwitch' => false,
        ];

        $customers = Customer::orderBy('name')->get();

        return view('sales.customers.index', compact('commandbar', 'customers'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Customer',
            'showViewSwitch' => false,
        ];

        $available_tags = ['Corporate', 'Retail', 'VIP', 'Local', 'International', 'Distributor'];

        return view('sales.customers.create', compact('commandbar', 'available_tags'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'tags' => 'array',
            'notes' => 'nullable|string',
        ]);

        $id = now()->timestamp;

        return redirect()->route('sales.customers.index')->with('success', 'Customer saved (demo) ID '.$id);
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Customer',
            'showViewSwitch' => false,
        ];

        $customer = Customer::findOrFail($id);
        $available_tags = ['Corporate', 'Retail', 'VIP', 'Local', 'International', 'Distributor'];

        return view('sales.customers.edit', compact('commandbar', 'customer', 'available_tags'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'tags' => 'array',
            'notes' => 'nullable|string',
        ]);

        return redirect()->route('sales.customers.index')->with('success', 'Customer updated (demo) ID '.$id);
    }
}
