<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $email
 * @property string|null $phone
 * @property string|null $address
 * @property array|null $tags
 * @property string|null $notes
 * @property float $total_spend
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'tags',
        'notes',
        'total_spend',
    ];

    protected $casts = [
        'tags' => 'array',
        'total_spend' => 'decimal:2',
    ];
}
