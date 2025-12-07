<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BomComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bom_id',
        'component_product_id',
        'qty',
        'unit_cost',
        'subtotal',
    ];

    public function bom()
    {
        return $this->belongsTo(BomHeader::class, 'bom_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }

    public function componentProduct()
    {
        return $this->belongsTo(Product::class, 'component_product_id');
    }
}
