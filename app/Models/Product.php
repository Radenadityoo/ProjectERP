<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'track_inventory',
        'price',
        'cost',
        'category',
        'reference',
        'barcode',
        'company',
        'internal_notes',
        'uom',
        'quantity',
        'description',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'track_inventory' => 'boolean',
    ];

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }
}
