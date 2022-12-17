<?php

namespace App\Models;

use Jenssegers\Model\Model;

/**
 * @property string $id
 * @property string $image
 * @property string $symbol
 * @property string $name
 * @property float $price
 */
class Coin extends Model
{
    protected $fillable = [
        'id',
        'image',
        'symbol',
        'name',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
    ];
}
