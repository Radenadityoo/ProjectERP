<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Get invoices to link payments to
        $invoices = Invoice::with('customer')
            ->whereIn('status', ['posted', 'paid'])
            ->get();

        if ($invoices->isEmpty()) {
            return;
        }

        $paymentMethods = ['bank_transfer', 'cash', 'check', 'credit_card'];
        $paymentCounter = 1;

        foreach ($invoices as $invoice) {
            // Skip some invoices to create overdue/unpaid scenarios
            if ($paymentCounter % 3 == 0) {
                continue;
            }

            $paymentMethod = $paymentMethods[($paymentCounter - 1) % count($paymentMethods)];
            
            // Create payment with some variance in amount
            $paymentAmount = $invoice->total;
            
            // Sometimes create partial payments
            if ($paymentCounter % 4 == 0) {
                $paymentAmount = $invoice->total * 0.5;
            }

            $paymentDate = $invoice->invoice_date->copy()->addDays(rand(1, 5));
            
            Payment::updateOrCreate(
                [
                    'invoice_id' => $invoice->id,
                    'payment_number' => 'PAY/2025/' . str_pad($paymentCounter, 4, '0', STR_PAD_LEFT),
                ],
                [
                    'payment_date' => $paymentDate,
                    'customer_id' => $invoice->customer_id,
                    'invoice_ref' => $invoice->number,
                    'journal' => 'AR',
                    'payment_method' => $paymentMethod,
                    'amount' => $paymentAmount,
                    'currency_code' => 'IDR',
                    'exchange_rate' => 1,
                    'amount_base' => $paymentAmount,
                    'memo' => 'Payment for ' . $invoice->number,
                    'status' => $paymentAmount >= $invoice->total ? 'posted' : 'posted',
                ]
            );

            $paymentCounter++;
        }

        // Update invoice status based on payments
        $this->updateInvoicePaymentStatus();
    }

    /**
     * Update invoice status and amount_paid based on payments
     */
    private function updateInvoicePaymentStatus(): void
    {
        $invoices = Invoice::with('customer')->get();

        foreach ($invoices as $invoice) {
            // Sum all payments for this invoice
            $totalPaid = Payment::where('invoice_id', $invoice->id)->sum('amount_base');
            
            $invoice->update([
                'amount_paid' => $totalPaid,
                'status' => $totalPaid >= $invoice->total ? 'paid' : 'posted',
            ]);
        }
    }
}
