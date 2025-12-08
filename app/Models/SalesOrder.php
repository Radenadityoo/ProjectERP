<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'so_number',
        'customer_id',
        'order_date',
        'delivery_date',
        'status',
        'currency_code',
        'exchange_rate',
        'subtotal',
        'tax_amount',
        'total',
        'subtotal_base',
        'tax_amount_base',
        'total_base',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'exchange_rate' => 'decimal:6',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'subtotal_base' => 'decimal:2',
        'tax_amount_base' => 'decimal:2',
        'total_base' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
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
