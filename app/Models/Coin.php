<?php

namespace App\Models;

use Jenssegers\Model\Model;

/**
 * @property string $symbol
 * @property string $name
 * @property float $price
 * @property Platform $platform
 */
class Coin extends Model
{
    protected $fillable = [
        'symbol',
        'name',
        'price',
        'platform',
    ];

    protected $casts = [
        'price' => 'float',
        'platform' => Platform::class,
    ];
}
