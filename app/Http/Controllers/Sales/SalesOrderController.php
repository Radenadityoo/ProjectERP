<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = SalesOrder::with('customer:id,name')
            ->select('id', 'so_number', 'customer_id', 'order_date', 'delivery_date', 'status', 'total', 'total_base')
            ->orderByDesc('order_date')
            ->paginate(25);

        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'so_number' => $order->so_number,
                'customer' => $order->customer?->name,
                'order_date' => optional($order->order_date)->format('Y-m-d'),
                'delivery_date' => optional($order->delivery_date)->format('Y-m-d'),
                'status' => $order->status,
                'total' => currency(($order->total_base ?: $order->total), base_currency()),
            ];
        });

        $commandbar = [
            'title' => 'Sales Orders',
            'count' => $orders->count(),
            'showViewSwitch' => false,
            'searchParam' => 'q',
        ];

        return view('sales.orders.index', compact('commandbar', 'orders'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Sales Order',
            'showViewSwitch' => false,
        ];

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('sales.orders.create', compact('commandbar', 'customers', 'products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'status' => 'nullable|in:draft,confirmed,delivered,cancelled',
            'currency_code' => 'nullable|string|size:3',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'nullable|exists:products,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.tax' => 'nullable|numeric|min:0',
        ]);

        $currencyCode = strtoupper($data['currency_code'] ?? setting('currency.default', base_currency()));
        $rateToBase = currency_rate_to_base($currencyCode);

        $soNumber = 'SO-' . now()->format('YmdHis');

        $order = SalesOrder::create([
            'so_number' => $soNumber,
            'customer_id' => $data['customer_id'],
            'order_date' => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'currency_code' => $currencyCode,
            'exchange_rate' => $rateToBase,
            'notes' => $request->input('notes'),
        ]);

        $subtotal = 0;
        $taxTotal = 0;
        $subtotalBase = 0;
        $taxTotalBase = 0;

        foreach ($data['lines'] as $line) {
            $lineSubtotal = $line['quantity'] * $line['unit_price'];
            $lineTax = ($line['tax'] ?? 0) / 100 * $lineSubtotal;
            $lineSubtotalBase = convert_to_base($lineSubtotal, $currencyCode);
            $lineTaxBase = convert_to_base($lineTax, $currencyCode);

            SalesOrderItem::create([
                'sales_order_id' => $order->id,
                'product_id' => $line['product_id'] ?? null,
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'tax_rate' => $line['tax'] ?? 0,
                'subtotal' => $lineSubtotal,
                'currency_code' => $currencyCode,
                'exchange_rate' => $rateToBase,
                'unit_price_base' => convert_to_base($line['unit_price'], $currencyCode),
                'subtotal_base' => $lineSubtotalBase,
            ]);

            $subtotal += $lineSubtotal;
            $taxTotal += $lineTax;
            $subtotalBase += $lineSubtotalBase;
            $taxTotalBase += $lineTaxBase;
        }

        $order->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'subtotal_base' => $subtotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $subtotalBase + $taxTotalBase,
        ]);

        return redirect()->route('sales.orders.index')->with('success', 'Sales order saved');
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Sales Order',
            'showViewSwitch' => false,
        ];

        $orderModel = SalesOrder::with('items')->findOrFail($id);

        $order = [
            'id' => $orderModel->id,
            'so_number' => $orderModel->so_number,
            'customer_id' => $orderModel->customer_id,
            'order_date' => optional($orderModel->order_date)->format('Y-m-d'),
            'delivery_date' => optional($orderModel->delivery_date)->format('Y-m-d'),
            'status' => $orderModel->status,
            'payment_terms' => $orderModel->payment_terms ?? null,
            'pricelist' => $orderModel->pricelist ?? null,
            'lines' => $orderModel->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'tax' => $item->tax_rate,
                ];
            })->toArray(),
        ];

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);

        return view('sales.orders.edit', compact('commandbar', 'order', 'customers', 'products'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'status' => 'in:draft,confirmed,delivered,cancelled',
            'currency_code' => 'nullable|string|size:3',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'nullable|exists:products,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.tax' => 'nullable|numeric|min:0',
        ]);

        $order = SalesOrder::findOrFail($id);
        $currencyCode = strtoupper($data['currency_code'] ?? $order->currency_code ?? setting('currency.default', base_currency()));
        $rateToBase = currency_rate_to_base($currencyCode);

        $order->update([
            'customer_id' => $data['customer_id'],
            'order_date' => $data['order_date'],
            'delivery_date' => $data['delivery_date'] ?? null,
            'status' => $data['status'],
            'currency_code' => $currencyCode,
            'exchange_rate' => $rateToBase,
            'notes' => $request->input('notes'),
        ]);

        $order->items()->delete();

        $subtotal = 0;
        $taxTotal = 0;
        $subtotalBase = 0;
        $taxTotalBase = 0;
        foreach ($data['lines'] as $line) {
            $lineSubtotal = $line['quantity'] * $line['unit_price'];
            $lineTax = ($line['tax'] ?? 0) / 100 * $lineSubtotal;
            $lineSubtotalBase = convert_to_base($lineSubtotal, $currencyCode);
            $lineTaxBase = convert_to_base($lineTax, $currencyCode);

            SalesOrderItem::create([
                'sales_order_id' => $order->id,
                'product_id' => $line['product_id'] ?? null,
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'unit_price' => $line['unit_price'],
                'tax_rate' => $line['tax'] ?? 0,
                'subtotal' => $lineSubtotal,
                'currency_code' => $currencyCode,
                'exchange_rate' => $rateToBase,
                'unit_price_base' => convert_to_base($line['unit_price'], $currencyCode),
                'subtotal_base' => $lineSubtotalBase,
            ]);

            $subtotal += $lineSubtotal;
            $taxTotal += $lineTax;
            $subtotalBase += $lineSubtotalBase;
            $taxTotalBase += $lineTaxBase;
        }

        $order->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'subtotal_base' => $subtotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $subtotalBase + $taxTotalBase,
        ]);

        return redirect()->route('sales.orders.index')->with('success', 'Sales order updated');
    }
}
