<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'sales_order_id',
        'customer_id',
        'invoice_date',
        'due_date',
        'status',
        'currency_code',
        'exchange_rate',
        'subtotal',
        'tax_amount',
        'total',
        'subtotal_base',
        'tax_amount_base',
        'total_base',
        'amount_paid',
        'amount_paid_base',
        'reference',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'exchange_rate' => 'decimal:6',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'subtotal_base' => 'decimal:2',
        'tax_amount_base' => 'decimal:2',
        'total_base' => 'decimal:2',
        'amount_paid_base' => 'decimal:2',
    ];

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->tax_amount = $this->items->sum(function ($item) {
            return ($item->tax_rate / 100) * $item->subtotal;
        });
        $this->total = $this->subtotal + $this->tax_amount;
        $this->save();
    }
}
