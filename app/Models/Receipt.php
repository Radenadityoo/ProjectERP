<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['reference','supplier','received_at','notes'];

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'reference', 'reference');
    }
}
