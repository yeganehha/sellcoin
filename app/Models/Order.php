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
 * @property string transaction
 * @property string coin_id
 * @property string coin_symbol
 * @property string coin_name
 * @property string wallet
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
        'transaction' ,
        'coin_id' ,
        'coin_symbol' ,
        'coin_name' ,
        'coin_price' ,
        'price' ,
        'wallet' ,
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
     * @param int $id
     * @return static
     */
    public static function findWithId(int $id): self
    {
        return self::findOrFail($id);
    }

    /**
     * @param float $amount
     * @param int $platform_id
     * @param float $price
     * @param Coin $coin
     * @param string $wallet
     * @return Order
     * @throws \Throwable
     */
    public static function insert(float $amount , int $platform_id , float $price , Coin $coin , string $wallet ): self
    {
        $order = new self();
        $order->edit(OrderStatusEnum::Wait,null,$amount,$price,$platform_id,$coin , $wallet);
        return $order;
    }

    /**
     * @param OrderStatusEnum|null $status
     * @param string|null $transaction
     * @param float|null $amount
     * @param float|null $price
     * @param int|null $platform_id
     * @param Coin|null $coin
     * @param string|null $wallet
     * @return Order
     * @throws \Throwable
     */
    public function edit(OrderStatusEnum|null $status = null, string|null $transaction = null, float|null $amount = null , float|null $price = null , int|null $platform_id = null  , Coin|null $coin = null , string|null $wallet = null): self
    {
        if( $status != null)
            $this->status = $status;
        if( $amount != null)
            $this->amount = $amount;
        if( $transaction != null)
            $this->transaction = $transaction;
        if( $platform_id != null)
            $this->platform_id = $platform_id;
        if( $price != null)
            $this->price = $price;
        if( $wallet != null)
            $this->wallet = $wallet;
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
