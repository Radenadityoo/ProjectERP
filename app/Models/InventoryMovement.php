<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'reference_type',
        'reference_id',
        'quantity_in',
        'quantity_out',
        'notes',
    ];

    protected $casts = [
        'quantity_in' => 'decimal:2',
        'quantity_out' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getNetQuantityAttribute()
    {
        return $this->quantity_in - $this->quantity_out;
    }
}
