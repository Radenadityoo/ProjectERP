<?php

namespace App\Http\Controllers\Invoicing;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $commandbar = [
            'title' => 'Payments',
            'showViewSwitch' => false,
        ];

        $payments = Payment::with('customer', 'invoice')
            ->orderBy('payment_date', 'desc')
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'payment_number' => $p->payment_number,
                    'customer' => $p->customer?->name ?? 'N/A',
                    'invoice_ref' => $p->invoice_ref ?? $p->invoice?->number ?? 'N/A',
                    'payment_date' => $p->payment_date instanceof \Carbon\Carbon ? $p->payment_date->format('Y-m-d') : $p->payment_date,
                    'amount' => currency($p->amount, 'IDR'),
                    'payment_method' => $p->payment_method,
                    'status' => ucfirst($p->status),
                ];
            })
            ->toArray();

        $customers = Customer::orderBy('name')->get();

        return view('invoicing.payments.index', compact('commandbar', 'payments', 'customers'));
    }

    public function create()
    {
        $commandbar = [
            'title' => 'Register Payment',
            'showViewSwitch' => false,
        ];

        $customers = Customer::orderBy('name')->get();
        $invoices = Invoice::with('customer')
            ->whereIn('status', ['sent', 'partial'])
            ->orderBy('invoice_date', 'desc')
            ->get()
            ->map(function($inv) {
                return [
                    'id' => $inv->id,
                    'number' => $inv->number,
                    'customer_id' => $inv->customer_id,
                    'amount_due' => $inv->total - $inv->amount_paid,
                ];
            });

        $journals = [
            ['id' => 1, 'name' => 'Bank - BCA'],
            ['id' => 2, 'name' => 'Bank - Mandiri'],
            ['id' => 3, 'name' => 'Cash'],
        ];

        return view('invoicing.payments.create', compact('commandbar', 'customers', 'invoices', 'journals'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'payment_number' => 'required|string|unique:payments,payment_number',
            'payment_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'invoice_ref' => 'nullable|string',
            'journal' => 'required|string',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'memo' => 'nullable|string',
        ]);

        $data['status'] = 'posted';
        $payment = Payment::create($data);

        // Update invoice if linked
        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                $newAmountPaid = (float)$invoice->amount_paid + (float)$payment->amount;
                $newStatus = $newAmountPaid >= (float)$invoice->total ? 'paid' : 'partial';
                
                $invoice->update([
                    'amount_paid' => $newAmountPaid,
                    'status' => $newStatus,
                ]);
            }
        }

        return redirect()
            ->route('invoicing.payments.index')
            ->with('success', 'Payment registered successfully.');
    }
}
