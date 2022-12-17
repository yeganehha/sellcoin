<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property float amount
 * @property int platform_id
 * @property string currency_id
 * @property string currency_symbol
 * @property string currency_name
 * @property float currency_price
 * @property float price
 * @property OrderStatusEnum status
 * @property Platform platform
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount' ,
        'platform_id' ,
        'currency_id' ,
        'currency_symbol' ,
        'currency_name' ,
        'currency_price' ,
        'price' ,
        'status' ,
    ];

    protected $casts = [
        'amount' => 'float',
        'platform_id' => 'int',
        'currency_price' => 'float',
        'price' => 'float',
        'status' => OrderStatusEnum::class,
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class)->withTrashed();
    }
}
