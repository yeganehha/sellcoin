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


    /**
     * @param float $amount
     * @param int $platform_id
     * @param float $price
     * @param Coin $coin
     * @return Order
     */
    public static function insert(float $amount , int $platform_id , float $price , Coin $coin ): self
    {
        $order = new self();
        $order->edit(OrderStatusEnum::Wait,$amount,$price,$platform_id,$coin);
        return $order;
    }

    /**
     * @param OrderStatusEnum|null $status
     * @param float|null $amount
     * @param float|null $price
     * @param int|null $platform_id
     * @param Coin|null $coin
     * @return Order
     * @throws \Throwable
     */
    public function edit(OrderStatusEnum|null $status = null, float|null $amount = null , float|null $price = null , int|null $platform_id = null  , Coin|null $coin = null): self
    {
        if( $status != null)
            $this->status = $status;
        if( $amount != null)
            $this->amount = $amount;
        if( $platform_id != null)
            $this->platform_id = $platform_id;
        if( $price != null)
            $this->price = $price;
        if( $coin != null) {
            $this->coin_id = $coin->id;
            $this->coin_name = $coin->name;
            $this->coin_price = $coin->price;
            $this->coin_symbol = $coin->symbol;
        }
        $this->saveOrFail();
        return $this;
    }
}
