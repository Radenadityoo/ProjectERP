<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturing extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'product_id',
        'quantity',
        'deadline',
        'status',
        'bom_id',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function bom()
    {
        return $this->belongsTo(BomHeader::class, 'bom_id');
    }
}
