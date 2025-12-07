<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = ['reference','customer','shipped_at','notes'];

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'reference', 'reference');
    }
}
