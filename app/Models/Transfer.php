<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['reference','from_location','to_location','transferred_at','notes'];

    public function movements()
    {
        return $this->hasMany(StockMovement::class, 'reference', 'reference');
    }
}
