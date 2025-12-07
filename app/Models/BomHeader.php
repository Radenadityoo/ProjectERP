<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int|null $product_id
 * @property float $quantity
 * @property float $total_cost
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class BomHeader extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'product_id',
        'quantity',
        'total_cost',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function components()
    {
        return $this->hasMany(BomComponent::class, 'bom_id');
    }
}
