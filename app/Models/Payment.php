<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $payment_number
 * @property string $payment_date
 * @property int|null $customer_id
 * @property int|null $invoice_id
 * @property string|null $invoice_ref
 * @property string $journal
 * @property string $payment_method
 * @property float $amount
 * @property string|null $memo
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Payment extends Model
{
    protected $fillable = [
        'payment_number',
        'payment_date',
        'customer_id',
        'invoice_id',
        'invoice_ref',
        'journal',
        'payment_method',
        'amount',
        'memo',
        'status',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
