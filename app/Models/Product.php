<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
    ];
}
