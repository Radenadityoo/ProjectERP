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
        $payments = Payment::with('customer', 'invoice')
            ->orderBy('payment_date', 'desc')
            ->paginate(25);

        $payments->getCollection()->transform(function($p) {
            return [
                'id' => $p->id,
                'payment_number' => $p->payment_number,
                'customer' => $p->customer?->name ?? 'N/A',
                'invoice_ref' => $p->invoice_ref ?? $p->invoice?->number ?? 'N/A',
                'payment_date' => $p->payment_date instanceof \Carbon\Carbon ? $p->payment_date->format('Y-m-d') : $p->payment_date,
                'amount' => currency($p->amount_base ?? $p->amount, base_currency()),
                'payment_method' => $p->payment_method,
                'status' => ucfirst($p->status),
            ];
        });

        $commandbar = [
            'title' => 'Payments',
            'count' => $payments->total(),
            'showViewSwitch' => false,
            'searchParam' => 'q',
        ];

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
                    'amount_due' => ($inv->total_base ?? $inv->total) - ($inv->amount_paid_base ?? $inv->amount_paid ?? 0),
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
            'currency_code' => 'nullable|string|size:3',
            'memo' => 'nullable|string',
        ]);

        $currencyCode = strtoupper($data['currency_code'] ?? setting('currency.default', base_currency()));
        $rateToBase = currency_rate_to_base($currencyCode);
        $amountBase = convert_to_base($data['amount'], $currencyCode);

        $data['status'] = 'posted';
        $data['currency_code'] = $currencyCode;
        $data['exchange_rate'] = $rateToBase;
        $data['amount_base'] = $amountBase;

        $payment = Payment::create($data);

        // Update invoice if linked
        if ($payment->invoice_id) {
            $invoice = Invoice::find($payment->invoice_id);
            if ($invoice) {
                $newAmountPaid = (float) $invoice->amount_paid + (float) $payment->amount;
                $newAmountPaidBase = (float) ($invoice->amount_paid_base ?? 0) + (float) $payment->amount_base;

                $targetTotalBase = (float) ($invoice->total_base ?? convert_to_base($invoice->total, $invoice->currency_code ?? base_currency()));
                $newStatus = $newAmountPaidBase >= $targetTotalBase ? 'paid' : 'partial';

                $invoice->update([
                    'amount_paid' => $newAmountPaid,
                    'amount_paid_base' => $newAmountPaidBase,
                    'status' => $newStatus,
                ]);
            }
        }

        return redirect()
            ->route('invoicing.payments.index')
            ->with('success', 'Payment registered successfully.');
    }
}
