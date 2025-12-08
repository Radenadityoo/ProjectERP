<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\SalesOrder;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with('customer:id,name')
            ->select('id', 'number', 'customer_id', 'invoice_date', 'due_date', 'status', 'total', 'total_base')
            ->orderByDesc('invoice_date')
            ->paginate(25);

        $invoices->getCollection()->transform(function ($invoice) {
            return [
                'id' => $invoice->id,
                'number' => $invoice->number,
                'customer' => $invoice->customer?->name,
                'invoice_date' => optional($invoice->invoice_date)->format('Y-m-d'),
                'due_date' => optional($invoice->due_date)->format('Y-m-d'),
                'status' => $invoice->status,
                'total' => currency(($invoice->total_base ?: $invoice->total), base_currency()),
            ];
        });

        $commandbar = [
            'title' => 'Invoices',
            'count' => $invoices->total(),
            'showViewSwitch' => false,
            'searchParam' => 'q',
        ];

        return view('invoicing.invoices.index', compact('commandbar', 'invoices'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Create Invoice',
            'showViewSwitch' => false,
        ];

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        $salesOrders = SalesOrder::with('customer')->orderByDesc('order_date')->get(['id', 'so_number', 'customer_id']);

        $journals = [
            ['id' => 1, 'name' => 'Customer Invoices'],
            ['id' => 2, 'name' => 'Credit Notes'],
        ];

        return view('invoicing.invoices.create', compact('commandbar', 'customers', 'products', 'journals', 'salesOrders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'nullable|in:draft,posted,paid,cancelled,overdue',
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

        $number = 'INV/' . now()->format('Ymd/His');

        $invoice = Invoice::create([
            'number' => $number,
            'sales_order_id' => $data['sales_order_id'] ?? null,
            'customer_id' => $data['customer_id'],
            'invoice_date' => $data['invoice_date'],
            'due_date' => $data['due_date'],
            'status' => $data['status'] ?? 'draft',
            'currency_code' => $currencyCode,
            'exchange_rate' => $rateToBase,
            'reference' => $request->input('reference'),
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

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
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

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'subtotal_base' => $subtotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $subtotalBase + $taxTotalBase,
        ]);

        return redirect()->route('invoicing.invoices.index')->with('success', 'Invoice saved');
    }

    public function edit($id)
    {
        $commandbar = [
            'title' => 'Edit Invoice',
            'showViewSwitch' => false,
        ];

        $invoiceModel = Invoice::with('items')->findOrFail($id);

        $invoice = [
            'id' => $invoiceModel->id,
            'number' => $invoiceModel->number,
            'customer_id' => $invoiceModel->customer_id,
            'sales_order_id' => $invoiceModel->sales_order_id,
            'invoice_date' => optional($invoiceModel->invoice_date)->format('Y-m-d'),
            'due_date' => optional($invoiceModel->due_date)->format('Y-m-d'),
            'reference' => $invoiceModel->reference,
            'status' => $invoiceModel->status,
            'lines' => $invoiceModel->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name,
                    'label' => $item->description,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'tax' => $item->tax_rate,
                ];
            })->toArray(),
        ];

        $customers = Customer::orderBy('name')->get(['id', 'name']);
        $products = Product::orderBy('name')->get(['id', 'name', 'price']);
        $salesOrders = SalesOrder::with('customer')->orderByDesc('order_date')->get(['id', 'so_number', 'customer_id']);

        $journals = [
            ['id' => 1, 'name' => 'Customer Invoices'],
            ['id' => 2, 'name' => 'Credit Notes'],
        ];

        return view('invoicing.invoices.edit', compact('commandbar', 'invoice', 'customers', 'products', 'journals', 'salesOrders'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'sales_order_id' => 'nullable|exists:sales_orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'status' => 'in:draft,posted,paid,cancelled,overdue',
            'currency_code' => 'nullable|string|size:3',
            'lines' => 'required|array|min:1',
            'lines.*.product_id' => 'nullable|exists:products,id',
            'lines.*.description' => 'nullable|string',
            'lines.*.quantity' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.tax' => 'nullable|numeric|min:0',
        ]);

        $invoice = Invoice::findOrFail($id);
        $currencyCode = strtoupper($data['currency_code'] ?? $invoice->currency_code ?? setting('currency.default', base_currency()));
        $rateToBase = currency_rate_to_base($currencyCode);

        $invoice->update([
            'customer_id' => $data['customer_id'],
            'sales_order_id' => $data['sales_order_id'] ?? null,
            'invoice_date' => $data['invoice_date'],
            'due_date' => $data['due_date'],
            'status' => $data['status'],
            'currency_code' => $currencyCode,
            'exchange_rate' => $rateToBase,
            'reference' => $request->input('reference'),
            'notes' => $request->input('notes'),
        ]);

        $invoice->items()->delete();

        $subtotal = 0;
        $taxTotal = 0;
        $subtotalBase = 0;
        $taxTotalBase = 0;
        foreach ($data['lines'] as $line) {
            $lineSubtotal = $line['quantity'] * $line['unit_price'];
            $lineTax = ($line['tax'] ?? 0) / 100 * $lineSubtotal;
            $lineSubtotalBase = convert_to_base($lineSubtotal, $currencyCode);
            $lineTaxBase = convert_to_base($lineTax, $currencyCode);

            InvoiceItem::create([
                'invoice_id' => $invoice->id,
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

        $invoice->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxTotal,
            'total' => $subtotal + $taxTotal,
            'subtotal_base' => $subtotalBase,
            'tax_amount_base' => $taxTotalBase,
            'total_base' => $subtotalBase + $taxTotalBase,
        ]);

        return redirect()->route('invoicing.invoices.index')->with('success', 'Invoice updated');
    }
}
