<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
