<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id', 'type', 'reference', 'quantity', 'source', 'destination', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
