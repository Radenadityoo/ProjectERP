<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $po_number
 * @property int|null $vendor_id
 * @property \Illuminate\Support\Carbon $order_date
 * @property \Illuminate\Support\Carbon|null $expected_arrival
 * @property string|null $reference
 * @property string $currency
 * @property string $status
 * @property float $subtotal
 * @property float $tax_amount
 * @property float $total
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'vendor_id',
        'order_date',
        'expected_arrival',
        'reference',
        'currency',
        'status',
        'subtotal',
        'tax_amount',
        'total',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_arrival' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum('subtotal');
        $this->total = $this->subtotal + $this->tax_amount;
        $this->save();
    }
}
