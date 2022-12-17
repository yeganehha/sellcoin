<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property float amount
 * @property int platform_id
 * @property string coin_id
 * @property string coin_symbol
 * @property string coin_name
 * @property float coin_price
 * @property float price
 * @property OrderStatusEnum status
 * @property Platform platform
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount' ,
        'platform_id' ,
        'coin_id' ,
        'coin_symbol' ,
        'coin_name' ,
        'coin_price' ,
        'price' ,
        'status' ,
    ];

    protected $casts = [
        'amount' => 'float',
        'platform_id' => 'int',
        'coin_price' => 'float',
        'price' => 'float',
        'status' => OrderStatusEnum::class,
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class)->withTrashed();
    }
}
