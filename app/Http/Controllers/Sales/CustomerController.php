<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('name')->get();
        
        $commandbar = [
            'title' => 'Customers',
            'showViewSwitch' => false,
            'searchParam' => 'q',
        ];

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

        $data['total_spend'] = 0;
        $customer = Customer::create($data);

        return redirect()->route('sales.customers.index')->with('success', 'Customer created successfully.');
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
        $customer = Customer::findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'tags' => 'array',
            'notes' => 'nullable|string',
        ]);

        $customer->update($data);

        return redirect()->route('sales.customers.index')->with('success', 'Customer updated successfully.');
    }
}
