<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    protected $fillable = ['reference','reason','adjusted_at','notes'];

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'reference', 'reference');
    }
}
